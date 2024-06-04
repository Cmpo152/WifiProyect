<?php

/**
 * Scritp para destruir sesión activa del usuario
 * Autor: WifiTeam
 */

require 'config/config.php';

session_destroy();

header('Location: index.php');
