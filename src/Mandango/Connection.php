<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 * (c) Fábián Tamás László <giganetom@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango;

/**
 * A connection to a database on a MongoDB server or cluster
 *
 * @author Pablo Díez <pablodip@gmail.com>
 *
 * @api
 */
class Connection
{
    private $uri;
    private $options = [];
    private $driverOptions = [];
    private $manager;
    private $database;

    /**
     * Constructor.
     *
     * @param string $uri     The connection URI.
     * @param array  $options Connection string options (optional).
     * 
     * For options see https://docs.mongodb.org/manual/reference/connection-string/#connections-connection-options
     *
     * @api
     */
    public function __construct($uri, $database, array $options = array(), $driverOptions = array())
    {
        $this->uri      = $uri;
        $this->options  = $options;
        $this->database = $database;
        $this->manager = new \MongoDB\Driver\Manager($this->uri, $this->options);
    }

    /**
     * Returns the connection URI.
     *
     * @return string $uri The URI.
     *
     * @api
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Returns the database name.
     *
     * @return string The database name.
     *
     * @api
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Returns the connection string options.
     *
     * @return array The options.
     *
     * @api
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Returns the driver options.
     *
     * @return array The driver options.
     *
     * @api
     */
    public function getDriverOptions()
    {
        return $this->driverOptions;
    }

    /**
     * Returns the connection manager
     */
    public function getManager()
    {
        return $this->manager;
    }
}
