#!/bin/bash
# Script de deployment para Hostinger
# Este script configura git para usar merge en lugar de rebase

# Configurar estrategia de pull
git config pull.rebase false

# Hacer pull con merge
git pull origin main --no-rebase

# O alternativamente, hacer reset y pull limpio
# git fetch origin
# git reset --hard origin/main

echo "Deployment completado"

