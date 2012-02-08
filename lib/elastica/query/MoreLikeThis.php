<?php
/**
 * More Like This query
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Raul Martinez, Jr <juneym@gmail.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/mlt-query.html
 */
namespace elastica\query;

class MoreLikeThis extends Abstract_
{
	/**
	 * Adds field to mlt query
	 *
	 * @param array $fields Field names
	 * @return elastica\query\MoreLikeThis Current object
	 */
	public function setFields(array $fields) {
		return $this->setParam('mlt_fields', $fields);
	}

	/**
	 * Set the "like_text" value
	 *
	 * @param string $likeText
	 * @return elastica\query\MoreLikeThis This current object
	 */
	public function setLikeText($likeText) {
		$likeText = trim($likeText);
		return $this->setParam('like_text', $likeText);
	}

	/**
	 * @param float $boost Boost value
	 * @return elastica\query\MoreLikeThis Query object
	 */
	public function setBoost($boost) {
		return $this->setParam('boost', (float) $boost);
	}

	/**
	 * Set max_query_terms
	 *
	 * @param int $maxQueryTerms Max query terms value
	 * @return elastica\query\MoreLikeThis
	 */
	public function setMaxQueryTerms($maxQueryTerms) {
		return $this->setParam('max_query_terms', (int) $maxQueryTerms);
	}


	/**
	 * @param float $percentTermsToMatch Percentage
	 * @return elastica\query\MoreLikeThis
	 */
	public function setPercentTermsToMatch($percentTermsToMatch) {
		return $this->setParam('percent_terms_to_match', (float) $percentTermsToMatch);
	}

	/**
	 * @param int $minTermFreq
	 * @return elastica\query\MoreLikeThis
	 */
	public function setMinTermFrequency($minTermFreq) {
		return $this->setParam('min_term_freq', (int) $minTermFreq);
	}


	/**
	 * @param int $minDocFreq
	 * @return elastica\query\MoreLikeThis
	 */
	public function setMinDocFrequency($minDocFreq) {
		return $this->setParam('min_doc_freq', (int) $minDocFreq);
	}

	/**
	 * @param int $maxDocFreq
	 * @return elastica\query\MoreLikeThis
	 */
	public function setMaxDocFrequency($maxDocFreq) {
		return $this->setParam('max_doc_freq', (int) $maxDocFreq);
	}


	/**
	 * @param int $minWordLength
	 * @return elastica\query\MoreLikeThis
	 */
	public function setMinWordLength($minWordLength) {
		return $this->setParam('min_word_length', (int) $minWordLength);
	}

	/**
	 * @param int $maxWordLength
	 * @return elastica\query\MoreLikeThis
	 */
	public function setMaxWordLength($maxWordLength) {
		return $this->setParam('max_word_length', (int) $maxWordLength);
	}

	/**
	 * @param bool $boostTerms
	 * @return elastica\query\MoreLikeThis
	 * @link http://www.elasticsearch.org/guide/reference/query-dsl/mlt-query.html
	 */
	public function setBoostTerms($boostTerms) {
		return $this->setParam('boost_terms', (bool) $boostTerms);
	}

	/**
	 * @param string $analyzer
	 * @return elastica\query\MoreLikeThis
	 */
	public function setAnalyzer($analyzer) {
		$analyzer = trim($analyzer);
		return $this->setParam('analyzer', $analyzer);
	}

	/**
	 * @param array $stopWords
	 * @return elastica\query\MoreLikeThis
	 */
	public function setStopWords(array $stopWords) {
		return $this->setParam('stop_words', $stopWords);
	}
}
