<?php

class commentsStorage extends Storage {
  public function __construct() {
    parent::__construct(new JsonIO('comments.json'));
  }
}

?>