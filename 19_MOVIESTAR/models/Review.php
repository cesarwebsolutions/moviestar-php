<?php

    class Review {
           
        public $id;   
        public $rating; // nota
        public $review; // comentario
        public $users_id; // usuario que comentou
        public $movies_id; // filme que comentou

    }


    interface ReviewDAOInterface {

        public function buildReview($data);
        public function create(Review $review);
        public function getMoviesReview($id);
        public function hasAlreadyReviewed($id, $userId);
        public function getRatings($id);
    }