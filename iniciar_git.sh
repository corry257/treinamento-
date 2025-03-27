#!/bin/bash

echo "=== Configurando repositório Git local para repositório existente no GitHub ==="

# Solicita as informações necessárias
read -p "Nome do usuário ou organização no GitHub: " GITHUB_USER
read -p "Nome do repositório: " REPO_NAME

# Pergunta se a pessoa usa host personalizado
read -p "Você usa configuração SSH personalizada para múltiplas contas? (s/n): " MULTI_ACCOUNT

if [[ "$MULTI_ACCOUNT" =~ ^[Ss]$ ]]; then
    read -p "Nome do host personalizado no SSH (exemplo: github-pessoal ou github-trabalho): " CUSTOM_HOST
else
    CUSTOM_HOST="github.com"
fi

# Mostra a URL que será configurada
REMOTE_URL="git@$CUSTOM_HOST:$GITHUB_USER/$REPO_NAME.git"

echo "Configurando repositório com a URL remota:"
echo "$REMOTE_URL"

# Inicializa o repositório (caso não esteja inicializado)
if [ ! -d ".git" ]; then
    echo "Inicializando o repositório..."
    git init
fi

# Configura o remote origin
echo "Configurando o remote origin..."
git remote set-url origin "$REMOTE_URL"

# Exibe os remotes configurados
echo "Remotes configurados:"
git remote -v

# Faz pull com rebase para evitar conflitos
echo "Realizando git pull com rebase..."
git pull --rebase origin main

# Exibe o status para confirmar
git status

echo "Configuração concluída!"
