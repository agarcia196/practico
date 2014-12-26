<?php
	/*
	Copyright (C) 2013  John F. Arroyave Gutiérrez
						unix4you2@gmail.com

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
	*/

	/*
		Title: Modulo objetos
		Ubicacion *[/core/objetos.php]*.  Archivo de funciones relacionadas con las operaciones de objetos generados por la herramienta
	*/



/* ################################################################## */
/* ################################################################## */
	if ($accion=="cargar_objeto")
		{
			/*
				Function: cargar_objeto
				Abre los objetos creados con practico como formularios, informes, etc.

				Variables de entrada:

					objeto - Cadena con la representacion del objeto en formato frm:xxx  inf:xxx   o similares donde se pueden tener multiples parametros separados por el caracter de *dos puntos*.  El primer parametro indicado despues del tipo de objeto indica el ID interno del objeto creado por practico.

				Codigo de ejemplo para llamadas a objetos comunes:

					(start code)
						frm:1:1:documento:123  //Llama al formulario con id=1, dentro de una ventana y buscando por el valor 123 en el campo documento
						inf:1:1:htm:Informes:0 //Llama el informe con id=1, dentro de una ventana, en formato HTML con el estilo CSS Informes y sin ser embebido
					(end)

				Parametros adicionales para formularios:
					
					parametros[2] - indica si es cargado en ventana o escritorio
					parametros[3] - campo a usar Si se llena el form desde un registro
					parametros[4] - valor a comparar para el campo de busqueda

				Parametros adicionales para informes:
					parametros[2] - indica si es cargado en ventana o escritorio
					parametros[3] - Formato utilizado para desplegar el informe: htm, xls
					parametros[4] - Estilo CSS utilizado para presentar el informe en caso de ser formato htm
					parametros[5] - Embebido? Si=1, No=0

				Salida:

					Objeto indicado por la variable de entrada cargado en pantalla mediante llamado a la funcion correspondiente.

				Ver tambien:
					<cargar_formulario> | <cargar_informe>
			*/

			$mensaje_error="";

			//Divide la cadena de objeto en partes conocidas
			$partes_objeto = explode(":", $objeto);
			if ($partes_objeto[0]!="frm" && $partes_objeto[0]!="inf")
				$mensaje_error=$MULTILANG_ObjError.": ".$partes_objeto[0];

			if ($mensaje_error=="")
				{
					//Si es un formulario lo llama con sus parámetros
					if ($partes_objeto[0]=="frm")
						{
							//Evalua si fueron enviados parametros adicionales
							if (@$partes_objeto[2]!="") $en_ventana=$partes_objeto[2];
							if (@$partes_objeto[3]!="") $campobase =$partes_objeto[3]; 
							if (@$partes_objeto[4]!="") $valorbase =$partes_objeto[4];
							cargar_formulario($partes_objeto[1],@$en_ventana,@$campobase,@$valorbase);
						}
					//Si es un informe lo llama con sus parámetros
					if ($partes_objeto[0]=="inf")
						{
							if (@$partes_objeto[2]!="") $en_ventana=$partes_objeto[2];
							if (@$partes_objeto[3]!="") $formato =$partes_objeto[3];
							if (@$partes_objeto[4]!="") $estilo =$partes_objeto[4];
							if (@$partes_objeto[5]!="") $embebido =$partes_objeto[5];
							cargar_informe($partes_objeto[1],@$en_ventana,@$formato,@$estilo,@$embebido);
						}
				}
			else
				{
					echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST">
						<input type="Hidden" name="accion" value="Ver_menu">
						<input type="Hidden" name="error_titulo" value="'.$MULTILANG_ErrorTiempoEjecucion.'">
						<input type="Hidden" name="error_descripcion" value="'.$mensaje_error.'">
						</form>
						<script type="" language="JavaScript"> document.cancelar.submit();  </script>';
				}
		}

/* ################################################################## */
/* ################################################################## */
	if ($accion=="guardar_configuracion")
		{
			/*
				Function: guardar_configuracion
				Actualiza los valores del archivo core/configuracion.php con los ingresados en el formulario por el administrador.  El archivo debe contar con permisos suficientes para que el usuario que ejecuta el servicio web pueda escribirlo.

				Variables de entrada:

					variables desde formulario

				Salida:

					Archivo de configuracion actualizado con los nuevos parametros
			*/

			$mensaje_error="";

			$hay_error=0;
			// Crea la cadena de salida con la configuracion de practico
$salida=sprintf("<?php
	/*
	Copyright (C) 2013  John F. Arroyave Gutiérrez
						unix4you2@gmail.com

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.


		Title: Configuracion base
		
		IMPORTANTE: La actualizacion de este archivo se deberia realizar por medio de la ventana de configuracion de la herramienta.  No altere estos valores manualmente a menos que sepa lo que hace.
		
		Ubicacion *[/core/configuracion.php]*.  Archivo que contiene la declaracion de variables basicas para conexion a bases de datos y otros

		Section: Variables de conexion

		Crea las variables de conexion para el motor de bases de datos, segmentos de direcciones, etc.  Ver ejemplo:

		(start code)
			ServidorBD='XXX';
			BaseDatos='XXX';
			UsuarioBD='XXX';
			PasswordBD='XXX';
			MotorBD='XXX';
			PuertoBD='';
		(end)
	*/

	\$ServidorBD='%s';	// Direccion IP o nombre de host
	\$BaseDatos='%s';   // Path completo cuando se trata de sqlite2, ej: '/path/to/database.sdb'
	\$UsuarioBD='%s';
	\$PasswordBD='%s';
	\$MotorBD='%s';		// Puede variar segun el driver PDO: mysql|pgsql|sqlite|sqlsrv|mssql|ibm|dblib|odbc|oracle|ifmx|fbd
	\$PuertoBD='%s';	// Vacio para predeterminado

	/*
		Section: Variables para aplicacion

		(start code)
			NombreRAD='XXX';			// Nombre del aplicativo
			VersionRAD='XXX';			// Version del aplicativo
			ArchivoCORE='';				// Script que procesa todos los formularios. Vacio para la misma pagina o index.php

			TablasCore='Core_';			// Prefijo de Tablas base para uso de Practico (Cuidado al cambiar)
			TablasApp='App_';			// Prefijo de Tablas de datos definidas por el usuario (Cuidado al cambiar)
		(end)

		*Llave de paso*

		Establezca cualquier valor en la siguiente variable para reforzar la seguridad. Cambiar esto despues de tener usuarios creados puede afectar la autenticacion
		Se recomienda establecer una llave en ambientes de produccion antes de trabajar. Cada usuario debe contar en su registro con una llave de paso equivalente al MD5 definido en este punto
		La llave de paso es utilizada tambien como una llave de consumo interno para WebServices.  Aunque se puede compartir con otros sitios o aplicativos, por seguridad se deberian utilizar llaves de paso generadas por el asistente.

		(start code)
			LlaveDePaso=''; //Predeterminado en vacio con MD5=d41d8cd98f00b204e9800998ecf8427e
		(end)
	*/

	\$NombreRAD='%s';
	\$ArchivoCORE='';
	\$TablasCore='%s';  // Cuidado al cambiar: Prefijo de Tablas base para uso de Practico
	\$TablasApp='%s';  // Cuidado al cambiar: Prefijo para Tablas de datos definidas por el usuario
	\$LlaveDePaso='%s';  // Valor unico para firmar los usuarios del aplicativo.  No debe ser cambiado despues de puesto en marcha a menos que se haga un update manual el usuario que no coincida con la llave no podra ingresar.
	\$ModoDepuracion=%s;
	\$BuscarActualizaciones=%s;
	\$ZonaHoraria='%s';
	\$IdiomaPredeterminado='%s';
	\$CaracteresCaptcha=%s;
	\$CodigoGoogleAnalytics='%s';
	
	// Tipo de motor usado para la autenticacion de usuarios
	\$Auth_TipoMotor='%s';
	\$Auth_ProtoTransporte='%s';
	
	// Configuracion LDAP - Auth_TipoMotor=ldap
	\$Auth_TipoEncripcion='%s';
	\$Auth_LDAPServidor='%s';
	\$Auth_LDAPPuerto='%s';
	\$Auth_LDAPDominio='%s';
	\$Auth_LDAPOU='%s';",$ServidorNEW,$BaseDatosNEW,$UsuarioBDNEW,$PasswordBDNEW,$MotorBDNEW,$PuertoBDNEW,$NombreRADNEW,$TablasCoreNEW,$TablasAppNEW,$LlaveDePasoNEW,$ModoDepuracionNEW,$BuscarActualizacionesNEW,$ZonaHorariaNEW,$IdiomaPredeterminadoNEW,$CaracteresCaptchaNEW,$CodigoGoogleAnalyticsNEW,$Auth_TipoMotorNEW,$Auth_ProtoTransporteNEW,$Auth_TipoEncripcionNEW,$Auth_LDAPServidorNEW,$Auth_LDAPPuertoNEW,$Auth_LDAPDominioNEW,$Auth_LDAPOUNEW);
			// Escribe el archivo de configuracion
			$archivo_config=fopen("core/configuracion.php","w");
			if($archivo_config==null)
				{
					$hay_error=1;
					$mensaje_error=$MULTILANG_ErrorEscribirConfig;
				}
			else
				{
					fwrite($archivo_config,$salida,strlen($salida)); 
					fclose($archivo_config);
				}
			if ($mensaje_error=="")
				{
					echo '<script type="" language="JavaScript"> document.core_ver_menu.submit();  </script>';
				}
			else
				{
					echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST">
						<input type="Hidden" name="accion" value="Ver_menu">
						<input type="Hidden" name="error_titulo" value="'.$MULTILANG_ErrorTiempoEjecucion.'">
						<input type="Hidden" name="error_descripcion" value="'.$mensaje_error.'">
						</form>
						<script type="" language="JavaScript"> document.cancelar.submit();  </script>';
				}
		}


/* ################################################################## */
/* ################################################################## */
	if ($accion=="guardar_oauth")
		{
			/*
				Function: guardar_oauth
				Actualiza las configuraciones para autenticacion por OAuth

				Variables de entrada:

					ID Client, Secret, URI - Para cada servicio de OAuth disponible

				Salida:

					Archivo de tokens y configuraciones de OAuth actualizado
			*/


$salida=sprintf("<?php
	/*
	Copyright (C) 2013  John F. Arroyave Gutiérrez
						unix4you2@gmail.com

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.


		Title: Configuracion Proveedores OAuth 1.0 y 2.0
		
		IMPORTANTE: La actualizacion de este archivo se deberia realizar por medio de la ventana de configuracion de la herramienta.  No altere estos valores manualmente a menos que sepa lo que hace.
		
		Ubicacion *[/core/ws_oauth.php]*.  Archivo que contiene la configuracion para autenticaciones externas con proveedores OAuth
	*/

	// Google
	\$APIGoogle_ClientId='%s';
	\$APIGoogle_ClientSecret='%s';
	\$APIGoogle_RedirectUri='%s';
	\$APIGoogle_Template='%s';

	// Facebook
	\$APIFacebook_ClientId='%s';
	\$APIFacebook_ClientSecret='%s';
	\$APIFacebook_RedirectUri='%s';
	\$APIFacebook_Template='%s';

	// Twitter
	\$APITwitter_ClientId='%s';
	\$APITwitter_ClientSecret='%s';
	\$APITwitter_RedirectUri='%s';
	\$APITwitter_Template='%s';

	// Dropbox
	\$APIDropbox_ClientId='%s';
	\$APIDropbox_ClientSecret='%s';
	\$APIDropbox_RedirectUri='%s';
	\$APIDropbox_Template='%s';

	// Flickr
	\$APIFlickr_ClientId='%s';
	\$APIFlickr_ClientSecret='%s';
	\$APIFlickr_RedirectUri='%s';
	\$APIFlickr_Template='%s';

	// Microsoft
	\$APIMicrosoft_ClientId='%s';
	\$APIMicrosoft_ClientSecret='%s';
	\$APIMicrosoft_RedirectUri='%s';
	\$APIMicrosoft_Template='%s';

	// Foursquare
	\$APIFoursquare_ClientId='%s';
	\$APIFoursquare_ClientSecret='%s';
	\$APIFoursquare_RedirectUri='%s';
	\$APIFoursquare_Template='%s';

	// Bitbucket
	\$APIBitbucket_ClientId='%s';
	\$APIBitbucket_ClientSecret='%s';
	\$APIBitbucket_RedirectUri='%s';
	\$APIBitbucket_Template='%s';

	// Salesforce
	\$APISalesforce_ClientId='%s';
	\$APISalesforce_ClientSecret='%s';
	\$APISalesforce_RedirectUri='%s';
	\$APISalesforce_Template='%s';

	// Yahoo
	\$APIYahoo_ClientId='%s';
	\$APIYahoo_ClientSecret='%s';
	\$APIYahoo_RedirectUri='%s';
	\$APIYahoo_Template='%s';

	// Box
	\$APIBox_ClientId='%s';
	\$APIBox_ClientSecret='%s';
	\$APIBox_RedirectUri='%s';
	\$APIBox_Template='%s';

	// Disqus
	\$APIDisqus_ClientId='%s';
	\$APIDisqus_ClientSecret='%s';
	\$APIDisqus_RedirectUri='%s';
	\$APIDisqus_Template='%s';

	// RightSignature
	\$APIRightSignature_ClientId='%s';
	\$APIRightSignature_ClientSecret='%s';
	\$APIRightSignature_RedirectUri='%s';
	\$APIRightSignature_Template='%s';

	// Fitbit
	\$APIFitbit_ClientId='%s';
	\$APIFitbit_ClientSecret='%s';
	\$APIFitbit_RedirectUri='%s';
	\$APIFitbit_Template='%s';

	// ScoopIt
	\$APIScoopIt_ClientId='%s';
	\$APIScoopIt_ClientSecret='%s';
	\$APIScoopIt_RedirectUri='%s';
	\$APIScoopIt_Template='%s';

	// Tumblr
	\$APITumblr_ClientId='%s';
	\$APITumblr_ClientSecret='%s';
	\$APITumblr_RedirectUri='%s';
	\$APITumblr_Template='%s';

	// StockTwits
	\$APIStockTwits_ClientId='%s';
	\$APIStockTwits_ClientSecret='%s';
	\$APIStockTwits_RedirectUri='%s';
	\$APIStockTwits_Template='%s';

	// LinkedIn
	\$APILinkedIn_ClientId='%s';
	\$APILinkedIn_ClientSecret='%s';
	\$APILinkedIn_RedirectUri='%s';
	\$APILinkedIn_Template='%s';

	// Instagram
	\$APIInstagram_ClientId='%s';
	\$APIInstagram_ClientSecret='%s';
	\$APIInstagram_RedirectUri='%s';
	\$APIInstagram_Template='%s';

	// SurveyMonkey
	\$APISurveyMonkey_ClientId='%s';
	\$APISurveyMonkey_ClientSecret='%s';
	\$APISurveyMonkey_RedirectUri='%s';
	\$APISurveyMonkey_Template='%s';

	// Eventful
	\$APIEventful_ClientId='%s';
	\$APIEventful_ClientSecret='%s';
	\$APIEventful_RedirectUri='%s';
	\$APIEventful_Template='%s';

	// XING
	\$APIXING_ClientId='%s';
	\$APIXING_ClientSecret='%s';
	\$APIXING_RedirectUri='%s';
	\$APIXING_Template='%s';
	
	// VK
	\$APIVK_ClientId='%s';
	\$APIVK_ClientSecret='%s';
	\$APIVK_RedirectUri='%s';
	\$APIVK_Template='%s';
	
	// Withings
	\$APIWithings_ClientId='%s';
	\$APIWithings_ClientSecret='%s';
	\$APIWithings_RedirectUri='%s';
	\$APIWithings_Template='%s';",$APIGoogle_ClientIdNEW,$APIGoogle_ClientSecretNEW,$APIGoogle_RedirectUriNEW,$APIGoogle_TemplateNEW,$APIFacebook_ClientIdNEW,$APIFacebook_ClientSecretNEW,$APIFacebook_RedirectUriNEW,$APIFacebook_TemplateNEW,$APITwitter_ClientIdNEW,$APITwitter_ClientSecretNEW,$APITwitter_RedirectUriNEW,$APITwitter_TemplateNEW,$APIDropbox_ClientIdNEW,$APIDropbox_ClientSecretNEW,$APIDropbox_RedirectUriNEW,$APIDropbox_TemplateNEW,$APIFlickr_ClientIdNEW,$APIFlickr_ClientSecretNEW,$APIFlickr_RedirectUriNEW,$APIFlickr_TemplateNEW,$APIMicrosoft_ClientIdNEW,$APIMicrosoft_ClientSecretNEW,$APIMicrosoft_RedirectUriNEW,$APIMicrosoft_TemplateNEW,$APIFoursquare_ClientIdNEW,$APIFoursquare_ClientSecretNEW,$APIFoursquare_RedirectUriNEW,$APIFoursquare_TemplateNEW,$APIBitbucket_ClientIdNEW,$APIBitbucket_ClientSecretNEW,$APIBitbucket_RedirectUriNEW,$APIBitbucket_TemplateNEW,$APISalesforce_ClientIdNEW,$APISalesforce_ClientSecretNEW,$APISalesforce_RedirectUriNEW,$APISalesforce_TemplateNEW,$APIYahoo_ClientIdNEW,$APIYahoo_ClientSecretNEW,$APIYahoo_RedirectUriNEW,$APIYahoo_TemplateNEW,$APIBox_ClientIdNEW,$APIBox_ClientSecretNEW,$APIBox_RedirectUriNEW,$APIBox_TemplateNEW,$APIDisqus_ClientIdNEW,$APIDisqus_ClientSecretNEW,$APIDisqus_RedirectUriNEW,$APIDisqus_TemplateNEW,$APIRightSignature_ClientIdNEW,$APIRightSignature_ClientSecretNEW,$APIRightSignature_RedirectUriNEW,$APIRightSignature_TemplateNEW,$APIFitbit_ClientIdNEW,$APIFitbit_ClientSecretNEW,$APIFitbit_RedirectUriNEW,$APIFitbit_TemplateNEW,$APIScoopIt_ClientIdNEW,$APIScoopIt_ClientSecretNEW,$APIScoopIt_RedirectUriNEW,$APIScoopIt_TemplateNEW,$APITumblr_ClientIdNEW,$APITumblr_ClientSecretNEW,$APITumblr_RedirectUriNEW,$APITumblr_TemplateNEW,$APIStockTwits_ClientIdNEW,$APIStockTwits_ClientSecretNEW,$APIStockTwits_RedirectUriNEW,$APIStockTwits_TemplateNEW,$APILinkedIn_ClientIdNEW,$APILinkedIn_ClientSecretNEW,$APILinkedIn_RedirectUriNEW,$APILinkedIn_TemplateNEW,$APIInstagram_ClientIdNEW,$APIInstagram_ClientSecretNEW,$APIInstagram_RedirectUriNEW,$APIInstagram_TemplateNEW,$APISurveyMonkey_ClientIdNEW,$APISurveyMonkey_ClientSecretNEW,$APISurveyMonkey_RedirectUriNEW,$APISurveyMonkey_TemplateNEW,$APIEventful_ClientIdNEW,$APIEventful_ClientSecretNEW,$APIEventful_RedirectUriNEW,$APIEventful_TemplateNEW,$APIXING_ClientIdNEW,$APIXING_ClientSecretNEW,$APIXING_RedirectUriNEW,$APIXING_TemplateNEW,$APIVK_ClientIdNEW,$APIVK_ClientSecretNEW,$APIVK_RedirectUriNEW,$APIVK_TemplateNEW,$APIWithings_ClientIdNEW,$APIWithings_ClientSecretNEW,$APIWithings_RedirectUriNEW,$APIWithings_TemplateNEW);

			$mensaje_error="";

			// Escribe el archivo de configuracion
			$archivo_config=fopen("core/ws_oauth.php","w");
			if($archivo_config==null)
				{
					$hay_error=1;
					$mensaje_error=$MULTILANG_ErrorEscribirConfig;
				}
			else
				{
					fwrite($archivo_config,$salida,strlen($salida)); 
					fclose($archivo_config);
				}
			if ($mensaje_error=="")
				{
					echo '<script type="" language="JavaScript"> document.core_ver_menu.submit();  </script>';
				}
			else
				{
					echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST">
						<input type="Hidden" name="accion" value="Ver_menu">
						<input type="Hidden" name="error_titulo" value="'.$MULTILANG_ErrorTiempoEjecucion.'">
						<input type="Hidden" name="error_descripcion" value="'.$mensaje_error.'">
						</form>
						<script type="" language="JavaScript"> document.cancelar.submit();  </script>';
				}
		}


