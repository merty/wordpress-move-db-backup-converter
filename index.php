<?php
/*
Project Name: WordPress Move Database Backup Converter
Description: Converts the serialized WordPress Move Database Backup file into a regular SQL dump.
Version: 1.0
Author: Mert Yazicioglu
Author URI: http://www.mertyazicioglu.com
License: GPL2
*/

/*  Copyright 2011  Mert Yazicioglu  (email : mert@mertyazicioglu.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// The array that will store the input files' names in
$files = array();

// Get all the input files inside the 'in' directory
if ( $d = opendir( 'in' ) ) {
    while ( false !== ( $entry = readdir( $d ) ) )
        if ( $entry != "." && $entry != ".." && ! is_dir( $entry ) )
            array_push( $files, $entry );
    closedir( $d );
} else {
	die( 'Could not get the list of input files!' );
}

// Process each input file...
foreach ( $files as $file ) {

	// Read the whole input file into a variable
	if ( $f = fopen( 'in/' . $file, 'r' ) ) {
		$serialized = fread( $f, filesize( 'in/' . $file ) );
		fclose( $f );
	} else {
		die( 'Could not open input file: ' . $file . '!' );
	}

	// Unserialize the queries
	$queries = unserialize( $serialized );

	// Create an output file
	$output = touch( 'out/' . $file );

	// Display an error message if creating an output file fails
	if ( ! $output )
		die( 'Could not create output file: ' . $file . '!' );

	// Open the output file and write each query one by one
	if ( $f = fopen( 'out/' . $file, 'w' ) ) {
		foreach ( $queries as $q )
			fwrite( $f, $q, strlen( $q ) );
		fclose( $f );
	} else {
		die( 'Could not write to output file: ' . $file . '!' );
	}

	echo $file . " has been converted successfully!\n";
}
