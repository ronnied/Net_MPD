<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Music Player Daemon API
 *
 * PHP Version 5
 * 
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available thorugh the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt. If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  Networking
 * @package   Net_MPD
 * @author    Graham Christensen <graham.christensen@itrebal.com>
 * @copyright 2006 Graham Christensen
 * @license   http://www.php.net/license/3_0.txt
 * @version   CVS: $ID:$
 */

/**
 * API for the database portion of Music Player Daemon commands
 *
 * Used for maintaining and working with the MPD database
 * 
 * @category  Networking
 * @package   Net_MPD
 * @author    Graham Christensen <graham.christensen@itrebal.com>
 * @copyright 2006 Graham Christensen
 * @license   http://www.php.net/license/3_0.txt
 * @version   CVS: $ID:$
 */
class Net_MPD_Database extends Net_MPD_Common
{
    /**
     * Case sensitive search for data in the database
     * 
     * @param $params array, array('search_field' => 'search for')
     * @param $caseSensitive bool True for case sensitivity, false for not
     * @return array
     */
    public function find($params, $caseSensitive = false)
        {
            $prms = array();
            foreach ($params as $key => $value) {
                $prms[] = $key;
                $prms[] = $value;
            }
        
            $cmd = $caseSensitive?'find':'search';
        
            try {
                $out = $this->runCommand($cmd, $prms);
                if (!isset($out['file'])) {
                    return array();
                }
                return $out['file'];
            } catch (PEAR_Exception $e) {
                throw new PEAR_Exception($e->getMessage(), $e);
            }
        }
  
    /**
     * List all metadata of matches to the search
     * 
     * @param $metadata1 string metadata to list
     * @param $metadata2 string metadata field to search in, optional
     * @param $search string data to search for in search field,
                      required if search field provided
                      * @return array
                      */
    public function getMetadata($metadata1, $metadata2 = null, $search = null)
        {
        
            //Make sure that if metadata2 is set, search is as well
            if (!is_null($metadata2)) {
                if (is_null($search)) {
                    return false;
                }
            }
            try {
                if (!is_null($metadata2)) {
                    $out = $this->runCommand('list', 
                                             array($metadata1,
                                                   $metadata2,
                                                   $search),
                                             1);
                } else {
                    $out = $this->runCommand('list', $metadata1,
                                             1);
                }
            
                return $out[$metadata1];
            } catch (PEAR_Exception $e) {
                throw new PEAR_Exception($e->getMessage(), $e);
            }
        }
  
    /**
     * Lists all files and folders in the directory recursively
     * 
     * @param $dir string directory to start in, optional
     * @return array
     */
    public function getAll($dir = '')
        {
            try {
                return $this->runCommand('listall', $dir, 1);
            } catch (PEAR_Exception $e) {
                throw new PEAR_Exception($e->getMessage(), $e);
            }
        }
  
    /**
     * Lists all files/folders recursivly, listing any related informaiton
     * 
     * @param $dir string directory to start in, optional
     * @return array
     */
    public function getAllInfo($dir = '')
        {
            try {
                return $this->runCommand('listallinfo', $dir);
            } catch (PEAR_Exception $e) {
                throw new PEAR_Exception($e->getMessage(), $e);
            }
        }
  
    /**
     * Lists content of the directory
     * 
     * @param $dir string directory to work in, optional
     * @return array
     */
    public function getInfo($dir = '')
        {
            try {
                return $this->runCommand('lsinfo', $dir);
            } catch (PEAR_Exception $e) {
                throw new PEAR_Exception($e->getMessage(), $e);
            }
        }
}