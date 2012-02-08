<?php

/**
 * Elastica searchable interface
 *
 * @category Xodoa
 * @package elastica
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
namespace elastica;

interface Searchable
{
	/**
	 * Searches results for a query
	 *
	 * TODO: Improve sample code
	 * {
	 *	 "from" : 0,
	 *	 "size" : 10,
	 *	 "sort" : {
	 *		  "postDate" : {"reverse" : true},
	 *		  "user" : { },
	 *		  "_score" : { }
	 *	  },
	 *	  "query" : {
	 *		  "term" : { "user" : "kimchy" }
	 *	  }
	 * }
	 *
	 * @param string|array|elastica\Query $query Array with all query data inside or a elastica\Query object
	 * @return elastica\ResultSet ResultSet with all results inside
	 */
	public function search($query);

	/**
	 * Counts results for a query
	 *
	 * If no query is set, matchall query is created
	 *
	 * @param string|array|elastica\Query $query Array with all query data inside or a elastica\Query object
	 * @return int number of documents matching the query
	 */
	public function count($query = '');
}
