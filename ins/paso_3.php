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
?>

<div align=center>
<table width="700" cellspacing=10>
	<tr>
		<td width=100><img src="../img/practico_login.png" border=0 ALT="Logo Practico" width="116" height="80"></td>
		<td valign=top><font size=2 color=black><br><b>
			[<?php echo $MULTILANG_TitInsPaso3; ?>]</b><br><br>
			<?php echo $MULTILANG_TitDesPaso3; ?>
		</font></td>
	</tr>
</table>
<hr>

<?php
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
			PlantillaActiva='XXX';		// Mascara visual con la definicion de hojas CSS e imagenes.  Ubicada en /skin
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
	\$PlantillaActiva='%s';
	\$ArchivoCORE='';
	\$TablasCore='%s';  // Cuidado al cambiar: Prefijo de Tablas base para uso de Practico
	\$TablasApp='%s';  // Cuidado al cambiar: Prefijo para Tablas de datos definidas por el usuario
	\$LlaveDePaso='%s';  // Valor unico para firmar los usuarios del aplicativo.  No debe ser cambiado despues de puesto en marcha a menos que se haga un update manual el usuario que no coincida con la llave no podra ingresar.
	\$ModoDepuracion=%s;
	\$ZonaHoraria='%s';
	\$IdiomaPredeterminado='%s';
	\$CaracteresCaptcha=%s;
	
	// Tipo de motor usado para la autenticacion de usuarios
	\$Auth_TipoMotor='%s';
	\$Auth_ProtoTransporte='%s';
	
	// Configuracion LDAP - Auth_TipoMotor=ldap
	\$Auth_TipoEncripcion='%s';
	\$Auth_LDAPServidor='%s';
	\$Auth_LDAPPuerto='%s';
	\$Auth_LDAPDominio='%s';
	\$Auth_LDAPOU='%s';",$ServidorNEW,$BaseDatosNEW,$UsuarioBDNEW,$PasswordBDNEW,$MotorBDNEW,$PuertoBDNEW,$NombreRADNEW,$PlantillaActivaNEW,$TablasCoreNEW,$TablasAppNEW,$LlaveDePasoNEW,$ModoDepuracionNEW,$ZonaHorariaNEW,$IdiomaPredeterminadoNEW,$CaracteresCaptchaNEW,$Auth_TipoMotorNEW,$Auth_ProtoTransporteNEW,$Auth_TipoEncripcionNEW,$Auth_LDAPServidorNEW,$Auth_LDAPPuertoNEW,$Auth_LDAPDominioNEW,$Auth_LDAPOUNEW);
	// Escribe el archivo de configuracion
	$archivo_config=fopen("../core/configuracion.php","w");
	if($archivo_config==null)
		{
			$hay_error=1;
			echo $MULTILANG_ErrorEscribirConfig;
		}
	else
		{
			fwrite($archivo_config,$salida,strlen($salida)); 
			fclose($archivo_config);
		}
	
	//Si no hay errores creando el archvio continua con la BD
	if (!$hay_error)
		{
			include("../core/configuracion.php");
			include("../core/conexiones.php");
			if ($hay_error)
				{
					echo $MULTILANG_ErrorConexBD;
				}
		}

	// Si no se encontro ningun error muestra mensaje OK
	if (!$hay_error)
		{
			echo '<table width="700" cellspacing=10><tr><td align=left><font size=2 color=black>
				'.$MULTILANG_InfoPaso3.'
			</td></tr></table>';
		}

?>

<br><br>
</div>

<?php
	abrir_barra_estado();

	$anterior=$paso-1;
	$siguiente=$paso+1;
	echo '<form name="regresar" action="" method="POST" style="display:inline; height: 0px; border-width: 0px; width: 0px; padding: 0; margin: 0;">
			<input type="Hidden" name="paso" value="'.$anterior.'">
			<input type="Hidden" name="Idioma" value="'.$Idioma.'">
			<input type="Button" class="BotonesEstado" value=" <<< '.$MULTILANG_Anterior.' " onclick="document.regresar.submit();">
		  </form>';

	if (!$hay_error)
		{
			echo '<form name="continuar" action="" method="POST" style="display:inline; height: 0px; border-width: 0px; width: 0px; padding: 0; margin: 0;">
				<input type="Hidden" name="paso" value="'.$siguiente.'">
				<input type="Hidden" name="aplicar_script_basedatos" value="1">
				<input type="Hidden" name="Idioma" value="'.$Idioma.'">
				<input type="Hidden" name="NombreCortoEmpresa" value="'.$NombreCortoEmpresa.'">
				<input type="Hidden" name="NombreAplicacion" value="'.$NombreAplicacion.'">
				<input type="Hidden" name="VersionAplicacion" value="'.$VersionAplicacion.'">
				<input type="Submit" class="BotonesEstadoCuidado" value=" '.$MULTILANG_BtnAplicarBD.' >>> " onclick="document.continuar.submit();">
				</form>';

			echo '<form name="continuar" action="" method="POST" style="display:inline; height: 0px; border-width: 0px; width: 0px; padding: 0; margin: 0;">
				<input type="Hidden" name="paso" value="'.$siguiente.'">
				<input type="Hidden" name="Idioma" value="'.$Idioma.'">
				<input type="Hidden" name="aplicar_script_basedatos" value="0">
				<input type="Submit" class="BotonesEstadoCuidado" value=" '.$MULTILANG_BtnNoAplicarBD.' >>> "  onclick="document.continuar.submit();">
				</form>';
		}
	cerrar_barra_estado();
?>

