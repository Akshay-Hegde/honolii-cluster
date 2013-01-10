<?php defined('BASEPATH') or exit('No direct script access allowed');

/* Messages */

$lang['streams:save_field_error'] 						= "Une erreur s'est produite lors de l'enregistrement de votre champ.";
$lang['streams:field_add_success']						= "Champ ajouté avec succès.";
$lang['streams:field_update_error']						= "Une erreur s'est produite lors de la mise à jour de votre champ.";
$lang['streams:field_update_success']					= "Champ mis à jour avec succès.";
$lang['streams:field_delete_error']						= "Une erreur s'est produite lors de la suppression de ce champ.";
$lang['streams:field_delete_success']					= "Champ supprimé avec succès.";
$lang['streams:view_options_update_error']				= "Une erreur s'est produite lors de la mise à jour des options de vue.";
$lang['streams:view_options_update_success']			= "Les options de vue ont été mises à jour avec succès.";
$lang['streams:remove_field_error']						= "Une erreur s'est produite lors de l'élimination de ce champ.";
$lang['streams:remove_field_success']					= "Champ effacé avec succès.";
$lang['streams:create_stream_error']					= "Une erreur s'est produite lors de la création de votre stream.";
$lang['streams:create_stream_success']					= "Stream créé avec succès.";
$lang['streams:stream_update_error']					= "Une erreur s'est produite lors de la mise à jour de ce stream.";
$lang['streams:stream_update_success']					= "Stream mis à jour avec succès.";
$lang['streams:stream_delete_error']					= "Une erreur s'est produite lors de la suppression de ce stream.";
$lang['streams:stream_delete_success']					= "Stream supprimé avec succès.";
$lang['streams:stream_field_ass_add_error']				= "Une erreur s'est produite lors de l'ajout de ce champ à ce stream.";
$lang['streams:stream_field_ass_add_success']			= "Champ ajouté au stream avec succès.";
$lang['streams:stream_field_ass_upd_error']				= "Une erreur s'est produite lors de la mise à jour de l'attribution de ce champ.";
$lang['streams:stream_field_ass_upd_success']			= "Attribution de champ mise à jour avec succès.";
$lang['streams:delete_entry_error']						= "Une erreur s'est produite lors de la suppression de cette entrée.";
$lang['streams:delete_entry_success']					= "Entrée supprimée avec succès.";
$lang['streams:new_entry_error']						= "Une erreur s'est produite lors de l'ajout de cette entrée.";
$lang['streams:new_entry_success']						= "Entrée ajoutée avec succès.";
$lang['streams:edit_entry_error']						= "Une erreur s'est produite lors de la mise à jour de cette entrée.";
$lang['streams:edit_entry_success']						= "Entrée mise à jour avec succès.";
$lang['streams:delete_summary']							= "Êtes-vous sûr de vouloir supprimer le stream <strong>%s</strong> ? Cela <strong>supprimera %s %s</strong> définitivement.";

/* Misc Errors */

$lang['streams:no_stream_provided']						= "Aucun stream n'a été fourni.";
$lang['streams:invalid_stream']							= "Stream non valide.";
$lang['streams:not_valid_stream']						= "n'est pas un stream valide.";
$lang['streams:invalid_stream_id']						= "ID de stream non valide.";
$lang['streams:invalid_row']							= "Ligne non valide.";
$lang['streams:invalid_id']								= "ID non valide.";
$lang['streams:cannot_find_assign']						= "Impossible de trouver l'attribution de champ.";
$lang['streams:cannot_find_pyrostreams']				= "Impossible de trouver PyroStreams.";
$lang['streams:table_exists']							= "Une table avec le slug %s existe déjà.";
$lang['streams:no_results']								= "Aucun résultat";
$lang['streams:no_entry']								= "Impossible de trouver l'entrée.";
$lang['streams:invalid_search_type']					= "n'est pas un type de recherche valide.";
$lang['streams:search_not_found']						= "Recherche non trouvée.";

/* Validation Messages */

$lang['streams:field_slug_not_unique']					= "Ce slug de champ est déjà utilisé.";
$lang['streams:not_mysql_safe_word']					= "Le champ %s est un terme réservé de MySQL.";
$lang['streams:not_mysql_safe_characters']				= "Le champ %s contient des caractères non autorisés.";
$lang['streams:type_not_valid']							= "Veuillez sélectionner un type de champ valide.";
$lang['streams:stream_slug_not_unique']					= "Ce slug de stream est déjà utilisé.";
$lang['streams:field_unique']							= "Le champ %s doit être unique.";
$lang['streams:field_is_required']						= "Le champ %s est obligatoire.";

/* Field Labels */

$lang['streams:label.field']							= "Champ";
$lang['streams:label.field_required']					= "Le champ est obligatoire";
$lang['streams:label.field_unique']						= "Le champ est unique";
$lang['streams:label.field_instructions']				= "Instructions de champ";
$lang['streams:label.make_field_title_column']			= "Faire du champ la colonne de titre";
$lang['streams:label.field_name']						= "Nom de champ";
$lang['streams:label.field_slug']						= "Slug de champ";
$lang['streams:label.field_type']						= "Type de champ";
$lang['streams:id']										= "ID";
$lang['streams:created_by']								= "Créé par";
$lang['streams:created_date']							= "Date de création";
$lang['streams:updated_date']							= "Date de modification";
$lang['streams:value']									= "Valeur";
$lang['streams:manage']									= "Gérer";
$lang['streams:search']									= "Rechercher";

/* Field Instructions */

$lang['streams:instr.field_instructions']				= "S'affichent dans un formulaire lors de la saisie ou de la modification des données.";
$lang['streams:instr.stream_full_name']					= "Nom complet de votre stream.";
$lang['streams:instr.slug']								= "Minuscules, lettres uniquement et tirets bas (_).";

/* Titles */

$lang['streams:assign_field']							= "Attribuer un champ à un stream";
$lang['streams:edit_assign']							= "Modifier l'attribution d'un stream";
$lang['streams:add_field']								= "Créer un champ";
$lang['streams:edit_field']								= "Modifier un champ";
$lang['streams:fields']									= "Champs";
$lang['streams:streams']								= "Streams";
$lang['streams:list_fields']							= "Lister les champs";
$lang['streams:new_entry']								= "Nouvelle entrée";
$lang['streams:stream_entries']							= "Entrées de stream";
$lang['streams:entries']								= "Entrées";
$lang['streams:stream_admin']							= "Administration de stream";
$lang['streams:list_streams']							= "Lister les streams";
$lang['streams:sure']									= "Êtes-vous sûr ?";
$lang['streams:field_assignments'] 						= "Attributions de champ au stream";
$lang['streams:new_field_assign']						= "Nouvelle attribution de champ";
$lang['streams:stream_name']							= "Nom de stream";
$lang['streams:stream_slug']							= "Slug de stream";
$lang['streams:about']									= "À propos";
$lang['streams:total_entries']							= "Entrées totales";
$lang['streams:add_stream']								= "Nouveau stream";
$lang['streams:edit_stream']							= "Modifier le stream";
$lang['streams:about_stream']							= "À propos de ce stream";
$lang['streams:title_column']							= "Colonne de titre";
$lang['streams:sort_method']							= "Méthode de tri";
$lang['streams:add_entry']								= "Ajouter une entrée";
$lang['streams:edit_entry']								= "Modifier une entrée";
$lang['streams:view_options']							= "Options de vue";
$lang['streams:stream_view_options']					= "Options de vue du stream";
$lang['streams:backup_table']							= "Sauvegarder la table du stream";
$lang['streams:delete_stream']							= "Supprimer le stream";
$lang['streams:entry']									= "Entrée";
$lang['streams:field_types']							= "Types de champ";
$lang['streams:field_type']								= "Type de champ";
$lang['streams:database_table']							= "Table de base de données";
$lang['streams:size']									= "Taille";
$lang['streams:num_of_entries']							= "Nombre d'entrées";
$lang['streams:num_of_fields']							= "Nombres de champs";
$lang['streams:last_updated']							= "Dernière modification";

/* Startup */

$lang['streams:start.add_one']							= "en ajouter un ici";
$lang['streams:start.no_fields']						= "Vous n'avez pas encore créé de champ. Pour commencer, vous pouvez";
$lang['streams:start.no_assign'] 						= "Il semblerait qu'il n'existe encore aucun champ pour ce stream. Pour commencer, vous pouvez";
$lang['streams:start.add_field_here']					= "ajouter un champ ici";
$lang['streams:start.create_field_here']				= "créer un champ ici";
$lang['streams:start.no_streams']						= "Il n'existe encore aucun stream, mais vous pouvez commencer par";
$lang['streams:start.adding_one']						= "en ajouter un";
$lang['streams:start.no_fields_to_add']					= "Aucun champ à ajouter";		
$lang['streams:start.no_fields_msg']					= "Il n'existe aucun champ à ajouter à ce stream. Dans PyroStreams, les champs peuvent être partagés entre les streams et les champs doivent être créés avant d'être ajoutés à un stream. Vous pouvez commencer par";
$lang['streams:start.adding_a_field_here']				= "ajouter un champ ici";
$lang['streams:start.no_entries']						= "Il n'existe encore aucune entrée pour <strong>%s</strong>. Pour commencer, vous pouvez";
$lang['streams:add_fields']								= "attribuer des champs";
$lang['streams:add_an_entry']							= "ajouter une entrée";
$lang['streams:to_this_stream_or']						= "à ce stream ou";
$lang['streams:no_field_assign']						= "Aucune attribution de champ";
$lang['streams:no_field_assign_msg']					= "Il semblerait qu'il n'existe encore aucun champ pour ce stream. Avant de saisir des données, vous devez d'abord";
$lang['streams:add_some_fields']						= "attribuer certains champs";
$lang['streams:start.before_assign']					= "Avant d'attribuer des champs à un stream, vous devez créer un champ. Vous pouvez";
$lang['streams:start.no_fields_to_assign']				= "Il semblerait qu'il n'existe aucun champ à attribuer. Avant de pouvoir attribuer un champ, vous devez ";

/* Buttons */

$lang['streams:yes_delete']								= "Oui, supprimer";
$lang['streams:no_thanks']								= "Non merci";
$lang['streams:new_field']								= "Nouveau champ";
$lang['streams:edit']									= "Modifier";
$lang['streams:delete']									= "Supprimer";
$lang['streams:remove']									= "Effacer";
$lang['streams:reset']									= "Réinitialiser";

/* Misc */

$lang['streams:field_singular']							= "champ";
$lang['streams:field_plural']							= "champs";
$lang['streams:by_title_column']						= "Par colonne de titre";
$lang['streams:manual_order']							= "Ordre manuel";
$lang['streams:stream_data_line']						= "Modifier les données basiques du stream.";
$lang['streams:view_options_line'] 						= "Choisir les colonnes à afficher sur la page qui liste les données.";
$lang['streams:backup_line']							= "Sauvegarder et télécharger la table du stream dans un fichier zip.";
$lang['streams:permanent_delete_line']					= "Supprimer définitivement un stream et toutes ses données.";
$lang['streams:choose_a_field_type']					= "Choisir un type de champ";
$lang['streams:choose_a_field']							= "Choisir un champ";

/* reCAPTCHA */

$lang['recaptcha_class_initialized'] 					= "Bibliothèque reCaptcha initialisée";
$lang['recaptcha_no_private_key']						= "Vous n'avez pas fourni de clé API pour reCaptcha";
$lang['recaptcha_no_remoteip'] 							= "Pour des raisons de sécurité, vous devez passer l'IP distante à reCaptcha";
$lang['recaptcha_socket_fail'] 							= "Impossible d'ouvrir le socket";
$lang['recaptcha_incorrect_response'] 					= "Réponse incorrecte à l'image de sécurité";
$lang['recaptcha_field_name'] 							= "Image de sécurité";
$lang['recaptcha_html_error'] 							= "Erreur lors du chargement de l'image de sécurité.  Veuillez ressayer ultérieurement";

/* Default Parameter Fields */

$lang['streams:max_length'] 							= "Longueur maximale";
$lang['streams:upload_location'] 						= "Emplacement de téléchargement";
$lang['streams:default_value'] 							= "Valeur par défaut";

/* End of file pyrostreams_lang.php */
