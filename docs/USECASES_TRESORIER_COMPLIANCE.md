# Use Cases - Trésorier & Compliance

Ce document décrit les cas d'utilisation pour les rôles **Trésorier** et **Compliance** du CRM.

---

## 1. Trésorier (Treasurer)

### 1.1 Suivi de la trésorerie en temps réel

| ID | Use Case | Description | Acteur |
|----|----------|-------------|--------|
| UC-T01 | Consulter le suivi trésorerie du jour | Visualiser le total des dépenses enregistrées pour la journée en cours | Trésorier |
| UC-T02 | Consulter le suivi trésorerie du mois | Visualiser le total des dépenses du mois en cours | Trésorier |
| UC-T03 | Consulter le suivi trésorerie par période | Filtrer et visualiser les dépenses sur une période personnalisée (date début - date fin) | Trésorier |
| UC-T04 | Consulter la moyenne quotidienne | Visualiser la moyenne des dépenses par jour sur la période affichée | Trésorier |
| UC-T05 | Rafraîchissement automatique | Le tableau de bord se met à jour automatiquement toutes les 5 minutes | Trésorier |

---

### 1.2 Gestion des dépenses

| ID | Use Case | Description | Acteur |
|----|----------|-------------|--------|
| UC-T06 | Créer une dépense | Enregistrer une nouvelle dépense (date, catégorie, description, montant, mode de paiement, note) | Trésorier |
| UC-T07 | Modifier une dépense | Mettre à jour les informations d'une dépense existante | Trésorier |
| UC-T08 | Supprimer une dépense | Supprimer une dépense de la liste | Trésorier |
| UC-T09 | Lister les dépenses | Consulter la liste des dépenses avec filtres (date, catégorie, mode de paiement) | Trésorier |
| UC-T10 | Créer une facture depuis une dépense | Depuis l'écran de création de dépense, rediriger vers la création de facture | Trésorier |

---

### 1.3 Gestion des factures

| ID | Use Case | Description | Acteur |
|----|----------|-------------|--------|
| UC-T11 | Créer une facture | Créer une nouvelle facture à partir d'un lead/prospect ou manuellement | Trésorier |
| UC-T12 | Modifier une facture | Mettre à jour une facture existante (avant encaissement) | Trésorier |
| UC-T13 | Imprimer une facture | Générer et imprimer le PDF d'une facture | Trésorier |
| UC-T14 | Supprimer une facture | Supprimer une facture (avec les contraintes métier applicables) | Trésorier |
| UC-T15 | Envoyer une relance | Envoyer un email de relance au client pour une facture impayée | Trésorier |
| UC-T16 | Lister les factures | Consulter la liste des factures avec recherche et filtres | Trésorier |

---

### 1.4 Tableau de bord financier

| ID | Use Case | Description | Acteur |
|----|----------|-------------|--------|
| UC-T17 | Consulter les dépenses mensuelles | Visualiser l'évolution des dépenses du mois vs mois précédent | Trésorier |
| UC-T18 | Filtrer les statistiques par période | Appliquer des filtres de date sur les statistiques du tableau de bord | Trésorier |

---

## 2. Compliance

### 2.1 Contrôle et audit

| ID | Use Case | Description | Acteur |
|----|----------|-------------|--------|
| UC-C01 | Audit des dépenses | Consulter l'historique des dépenses pour vérification et conformité | Compliance |
| UC-C02 | Audit des factures | Vérifier le cycle complet des factures (création, modification, paiement) | Compliance |
| UC-C03 | Traçabilité des opérations | Consulter qui a créé/modifié/supprimé une dépense ou facture (auteur, date) | Compliance |
| UC-C04 | Contrôle des montants | Vérifier la cohérence des montants et des modes de paiement | Compliance |

---

### 2.2 Validation et approbation

| ID | Use Case | Description | Acteur |
|----|----------|-------------|--------|
| UC-C05 | Valider une dépense | Approuver une dépense avant enregistrement définitif | Compliance |
| UC-C06 | Valider une facture | Contrôler et valider une facture avant envoi au client | Compliance |
| UC-C07 | Signaler une anomalie | Marquer une opération comme non conforme pour investigation | Compliance |

---

### 2.3 Conformité réglementaire

| ID | Use Case | Description | Acteur |
|----|----------|-------------|--------|
| UC-C08 | Vérifier les justificatifs | S'assurer que les dépenses sont accompagnées des pièces justificatives requises | Compliance |
| UC-C09 | Contrôle des catégories | Vérifier que les dépenses sont correctement catégorisées selon la charte comptable | Compliance |
| UC-C10 | Rapport de conformité | Générer un rapport des opérations conformes / non conformes sur une période | Compliance |

---

### 2.4 Documentation et reporting

| ID | Use Case | Description | Acteur |
|----|----------|-------------|--------|
| UC-C11 | Exporter les données pour audit | Exporter les données (dépenses, factures) au format requis pour les audits externes | Compliance |
| UC-C12 | Consulter les rapports de trésorerie | Accéder aux rapports financiers pour vérification de conformité | Compliance |

---

## Résumé des accès par rôle

| Module | Trésorier | Compliance |
|--------|-----------|------------|
| Suivi trésorerie temps réel | ✓ | ✓ (lecture) |
| Dépenses - CRUD | ✓ | ✓ (lecture + validation) |
| Factures - CRUD | ✓ | ✓ (lecture + validation) |
| Tableau de bord financier | ✓ | ✓ (lecture) |
| Audit / Traçabilité | - | ✓ |
| Rapports de conformité | - | ✓ |

---

*Document généré pour le CRM - Synapsis Pharma*
