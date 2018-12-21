<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

?>
<!DOCTYPE html>
<html>
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <script src="jquery-1.12.1.min.js"></script>
    <script src="jquery-ui-1.12.0/jquery-ui.min.js"></script>
    <script src="svg-pan-zoom.js"></script>
    <script src="neurosim.js"></script>
    <script src="jquery-svg/jquery.svg.min.js"></script>
    
    <!-- Bootstrap bits -->
    <script src="tether/js/tether.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="font-awesome-4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="tether/css/tether.min.css">
    <script src="bootbox.min.js"></script>
    
    <link rel="stylesheet" href="jquery-ui-1.12.0/jquery-ui.css">
    <link rel="stylesheet" href="neurosim.css">
    
    <!-- Three js for matrices and 3D visualisation -->
    <script src="three.min.js"></script>
    <script src="controls/TrackballControls.js"></script>
    <script src="three_animate.js"></script>

    <!-- slick carousel -->
    <script src="slick-1.6.0/slick/slick.min.js"></script>
    <link rel="stylesheet" href="slick-1.6.0/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="slick-1.6.0/slick/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="case_selection.css"/>

    <title>NeuroSim</title>
</head>
<body>
    <?= $this->fetch('content') ?>
</body>
</html>
