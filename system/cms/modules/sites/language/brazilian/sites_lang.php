<?php
//messages
$lang['site:not_super_admin']		=	'Você não é um Super Admin.';
$lang['site:really_delete']			=	'Tem certeza? Se você clicar neste botão o site "%s" será removido totalmente. Isso não poderá ser desfeito.';
$lang['site:exists']				=	'O Domínio do site deve ser único. Um site já está registrado com o domínio "%s"';
$lang['site:site_deleted']			=	'O site foi removido com êxito.';
$lang['site:site_delete_error']		=	'Os arquivos do site não foram removidos. Você terá que remover esses arquivos manualmente %s';
$lang['site:folder_exists']			=	'Há uma pasta que deve ser removido antes que você possa criar um site com o mesmo nome:<br />%s';
$lang['site:create_success']		=	'O site foi adicionado com sucesso.';
$lang['site:edit_success']			=	'Suas alterações no "%s" foram realizadas com sucesso.';
$lang['site:delete_error']			=	'Os registros do site não puderam ser removidos do banco de dados';
$lang['site:delete_manually']		=	'As seguintes pastas devem ser removidas manualmente:<br />%s';
$lang['site:db_error']				=	'As alterações no site não puderam ser salvas no banco de dados';
$lang['site:rename_notice']			=	'As seguintes pastas devem ser renomeadas manualmente:<br />%s';
$lang['site:rename_manually']		=	'Renomeie %s para %s';
$lang['site:create_manually']		=	'As seguintes pastas devem ser criadas manualmente: <br />%s';
$lang['site:admin_create_success']	=	'%s agora é um Super Admin';
$lang['site:user_exists']			=	'Desculpe, o e-mail "%s" já está registrado';
$lang['site:disable_self']			=	'Você não pode desabilitar você mesmo';
$lang['site:settings_success']		=	'As configurações foram atualizadas com sucesso';

//addon management messages
$lang['site:addon_exists']			=	'"%s" já existe. Você deve removê-lo antes de voltar a enviar';
$lang['site:addon_duplicate']		=	'"%s" está na pasta de um site específico e na pasta de compartilhamento. Para evitar duplicação um deve ser removido';
$lang['site:uninstall_success']		=	'"%s" foi desinstalado com êxito';
$lang['site:uninstall_error']		=	'"%s" não pôde ser desinstalado';
$lang['site:addon_not_specified']	=	'Você deve especificar um complemento para remover';
$lang['site:delete_success']		=	'"%s" foi removido com êxito';
$lang['site:manually_remove']		=	'O complemento naõ pôde ser removido completamente. Você deve remover "%s" manualmente';
$lang['site:delete_addon_error']	=	'"%s" não pôde ser removido';
$lang['site:upload_success']		=	'O %s foi enviado com sucesso';
$lang['site:install_success']		=	'"%s" foi instalado com sucesso';
$lang['site:install_error']			=	'"%s" não pôde ser instalado';
$lang['site:enable_success']		=	'"%s" foi habilitado com sucesso';
$lang['site:enable_error']			=	'"%s" não pôde ser habilitado';
$lang['site:disable_success']		=	'"%s" foi desabilitado com sucesso';
$lang['site:disable_error']			=	'"%s" não pôde ser desabilitado';
$lang['site:upgrade_success']		=	'"%s" foi atualizado com sucesso';
$lang['site:upgrade_error']			=	'"%s" não pôde ser atualizado';

//addons confirm messages
$lang['site:confirm_install']		=	'Se existir tabelas de uma instalação anterior elas poderão ser descartadas. Tem certeza de que deseja prosseguir com a instalação?';
$lang['site:confirm_uninstall']		=	'Todas as informações do banco de dados serão perdidas! Tem certeza de que deseja desinstalar?';
$lang['site:confirm_upgrade']		=	'Será feita uma tentativa para atualizar esse complemento. Você tem uma cópia de segurança em caso de falha?';
$lang['site:confirm_delete']		=	'Todas as informações do banco de dados e arquivos complementares serão perdidos! Tem certeza de que deseja prosseguir?';
$lang['site:confirm_shared_delete']	=	'Caso você remova este complemento isto irá afetar TODOS os sites! Tem certeza de que deseja prosseguir?';

//page titles
$lang['site:create_site']			=	'Adicionar site';
$lang['site:edit_site']				=	'Editando o site "%s"';
$lang['site:sites']					=	'Gerenciador Multisite';
$lang['site:user_manager']			=	'Gerenciador de Super Admin';
$lang['site:create_admin']			=	'Adicionar Super Admin';
$lang['site:edit_admin']			=	'Editando o Super Admin "%s"';
$lang['site:module_list']			=	'Módulos para %s';
$lang['site:shared_module_list']	=	'Módulos compartilhados';
$lang['site:widget_list']			=	'Widgets para %s';
$lang['site:shared_widget_list']	=	'Widgets compartilhados';
$lang['site:theme_list']			=	'Temas para %s';
$lang['site:shared_theme_list']		=	'Temas compartilhados';
$lang['site:shared_plugin_list']	=	'Plugins compartilhados';
$lang['site:modules']				=	'Módulos';
$lang['site:widgets']				=	'Widgets';
$lang['site:themes']				=	'Temas';
$lang['site:plugins']				=	'Plugins';

//page descriptions
$lang['site:edit_site_desc']		=	'Editar e remover sites com poucos cliques em seu mouse';
$lang['site:create_site_desc']		=	'Criar um site completamente novo diretamente dessa interface';
$lang['site:create_admin_desc']		=	'Gerenciar Super Admin adicionais que poderão criar e remover sites';
$lang['site:super_admin_list']		=	'Uma lista de todos os Super Admins';
$lang['site:settings_desc']			=	'Gerenciar configurações para a interface do Gerenciador Multisite';
$lang['site:manage_addons_desc']	=	'Gerenciar complementos específicos de um site assim como complementos compartilhados';


//labels
$lang['site:remove_admin']			=	'Remover Super Admin';
$lang['site:site']					=	'Site';
$lang['site:existing_sites']		=	'Sites existentes';
$lang['site:site_details']			=	'Detalhes do site';
$lang['site:descriptive_name']		=	'Nome descritivo';
$lang['site:domain']				=	'Domínio';
$lang['site:ref']					=	'Referência';
$lang['site:created_on']			=	'Desde';
$lang['site:manage']				=	'Gerenciar';
$lang['site:super_admins']			=	'Super Admins';
$lang['site:add_super_admin']		=	'Novo Super Admin';
$lang['site:first_admin']			=	'Admin principal (Admin)';
$lang['site:username']				=	'Nome de usuário';
$lang['site:email']					=	'E-mail';
$lang['site:active']				=	'Ativo';
$lang['site:last_login']			=	'Último acesso';
$lang['site:last_admin_login']		=	'Último acesso do Admin';
$lang['site:stats']					=	'Estatísticas';
$lang['site:addons']				=	'Complementos';
$lang['site:resource']				=	'Recurso';
$lang['site:usage']					=	'Utilizado';
$lang['site:tables']				=	'Tabelas de banco de dados:';
$lang['site:users']					=	'Usuários registrados:';
$lang['site:schema_version']		=	'Versão do esquema do banco de dados:';
$lang['site:settings']				=	'Configurações';
$lang['site:date_format']			=	'Formato de data';
$lang['site:lang_direction']		=	'Direção do idioma';
$lang['site:shared_title']			=	'Envie um %s compartilhado';
$lang['site:site_upload_title']		=	'Envie um %s apenas para este site';
$lang['site:upload_desc']			=	'Selecione um arquivo ZIP para enviar';
$lang['site:addons_upload']			=	'Envio de complementos';
$lang['site:allowed']				=	'Liberado';
$lang['site:disabled']				=	'Desabilitado';