#!/bin/bash

# Synchronisation automatique des photos de profil entre storage/app/public/profile-photos et public/storage/profile-photos

SRC="storage/app/public/profile-photos/"
DEST="public/storage/profile-photos/"

# Création du dossier destination s'il n'existe pas
mkdir -p "$DEST"

# Synchronisation (copie tous les fichiers nouveaux ou modifiés)
rsync -av --delete "$SRC" "$DEST"

echo "Synchronisation terminée !"
