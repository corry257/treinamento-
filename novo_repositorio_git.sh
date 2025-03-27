#!/bin/bash

echo "=== Configuração do Git para novo repositório ==="

# Pergunta os dados ao usuário
read -p "Nome da conta (exemplo: corry257 ou alan-neves): " GITHUB_USER
read -p "Nome do repositório (exemplo: meu_repositorio): " REPO_NAME
read -p "Nome da chave SSH (exemplo: ssh_corry257 ou alan-neves): " SSH_KEY_NAME
read -p "Nome do host SSH personalizado (exemplo: github-corry257 ou github-alan-neves, ou digite github.com para usar direto): " CUSTOM_HOST

# Caminho completo da chave SSH
SSH_KEY_PATH="$HOME/.ssh/$SSH_KEY_NAME"

# Arquivo de configuração SSH
CONFIG_FILE="$HOME/.ssh/config"

# Verifica se o Git está instalado
if ! command -v git &> /dev/null; then
    echo "Erro: Git não está instalado. Instale o Git e tente novamente."
    exit 1
fi

# Verifica se a chave SSH existe
if [ ! -f "$SSH_KEY_PATH" ]; then
    echo "Erro: Chave SSH '$SSH_KEY_PATH' não encontrada."
    exit 1
fi

# Garante que ~/.ssh/config existe
if [ ! -f "$CONFIG_FILE" ]; then
    touch "$CONFIG_FILE"
fi

# Adiciona ou atualiza a configuração SSH para o host personalizado
if grep -q "Host $CUSTOM_HOST" "$CONFIG_FILE"; then
    echo "Configuração SSH para $CUSTOM_HOST já existe. Verificando consistência..."
    CURRENT_KEY=$(awk "/Host $CUSTOM_HOST/,/IdentitiesOnly/" "$CONFIG_FILE" | grep IdentityFile | awk '{print $2}')
    if [ "$CURRENT_KEY" != "$SSH_KEY_PATH" ]; then
        echo "Atualizando caminho da chave SSH para $CUSTOM_HOST."
        sed -i "/Host $CUSTOM_HOST/,/IdentitiesOnly/ s|IdentityFile .*|IdentityFile $SSH_KEY_PATH|" "$CONFIG_FILE"
    fi
else
    echo "Adicionando configuração SSH para $CUSTOM_HOST no ~/.ssh/config"
    cat <<EOL >> "$CONFIG_FILE"

Host $CUSTOM_HOST
    HostName github.com
    User git
    IdentityFile $SSH_KEY_PATH
    IdentitiesOnly yes
EOL
fi

# Inicializa repositório Git local (com aviso se já existir)
if [ -d ".git" ]; then
    echo "Aviso: Diretório já possui um repositório Git. Remote origin será atualizado."
else
    git init
fi

# Cria um README.md mínimo, se não existir
if [ ! -f "README.md" ]; then
    echo "# $REPO_NAME" > README.md
    git add README.md
    git commit -m "Initial commit"
fi

# Configura a URL remota com o host personalizado
REMOTE_URL="git@$CUSTOM_HOST:$GITHUB_USER/$REPO_NAME.git"

if git remote get-url origin &>/dev/null; then
    git remote set-url origin "$REMOTE_URL"
else
    git remote add origin "$REMOTE_URL"
fi

# Mostra configurações finais
echo "Repositório configurado com sucesso:"
echo " - Repositório: $REPO_NAME"
echo " - Conta GitHub: $GITHUB_USER"
echo " - Host SSH: $CUSTOM_HOST"
echo " - Chave SSH: $SSH_KEY_PATH"
echo " - URL Remota: $REMOTE_URL"

# Testa conexão SSH
echo "Testando conexão SSH..."

if [ "$CUSTOM_HOST" == "github.com" ]; then
    ssh -T git@github.com
else
    ssh -T "$CUSTOM_HOST"
fi

# Aviso sobre push inicial
echo "Verificando existência do repositório remoto no GitHub..."
echo "Se o repositório '$REPO_NAME' ainda não existir no GitHub, o push abaixo falhará."
echo "Certifique-se de criar o repositório no GitHub antes de continuar."

# Realiza push inicial (se autorizado e se o repositório remoto existir)
git branch -M main
git push -u origin main

echo "Configuração finalizada. Se o push falhou, verifique se o repositório remoto existe e revise suas permissões SSH."
