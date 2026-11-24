#!/usr/bin/env bash
# Copia la base local (Postgres) a Supabase. Ejecutar en tu Mac:
#   SUPABASE_URL='postgresql://postgres.<ref>:<PASSWORD>@aws-0-<region>.pooler.supabase.com:5432/postgres' \
#     bash scripts/migrar-db-a-supabase.sh
# (Usa el "Session pooler" de Supabase, puerto 5432.)
set -euo pipefail

LOCAL_DB="${LOCAL_DB:-xtreme1}"
LOCAL_USER="${LOCAL_USER:-postgres}"
LOCAL_HOST="${LOCAL_HOST:-127.0.0.1}"
: "${SUPABASE_URL:?Define SUPABASE_URL con la cadena del Session pooler}"

DUMP="xtreme_$(date +%Y%m%d_%H%M).sql"

echo "1) Exportando base local '$LOCAL_DB'..."
pg_dump -h "$LOCAL_HOST" -U "$LOCAL_USER" -d "$LOCAL_DB" \
  --schema=public --no-owner --no-privileges --clean --if-exists \
  -f "$DUMP"
echo "   -> $DUMP ($(du -h "$DUMP" | cut -f1))"

echo "2) Importando en Supabase..."
psql "$SUPABASE_URL?sslmode=require" -v ON_ERROR_STOP=0 -f "$DUMP" 2>&1 \
  | grep -vE "does not exist, skipping|^SET$|^$" | tail -20

psql "$SUPABASE_URL?sslmode=require" -At -c "analyze;" >/dev/null || true
echo "3) Verificando conteo de filas por tabla en Supabase..."
psql "$SUPABASE_URL?sslmode=require" -At -c "
  select relname, n_live_tup from pg_stat_user_tables where schemaname='public' order by relname;" || true
echo "Listo. Compara con tu local: psql -U $LOCAL_USER -d $LOCAL_DB -c 'select count(*) from players;'"
