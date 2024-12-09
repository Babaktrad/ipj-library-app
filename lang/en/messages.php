<?php

return [
    'general' => [
        'something_went_wrong' => 'Something went wrong.',
    ],
    'auth' => [
        'register' => [
            'email_already_exists' => 'The email has already been taken.',
        ],
        'login' => [
            'email_not_found' => 'Username or password is incorrect.',
            'succeeded' => 'User logged in successfully.',
        ],
        'logout' => [
            'succeeded' => 'User logged out successfully.',
        ]
    ],
    'books' => [
        'read' => [
            'not_found' => 'Book not found.',
        ],
        'create' => [
            'succeeded' => 'Book created successfully.',
            'failed' => 'Failed to create book.',
        ],
        'update' => [
            'succeeded' => 'Book updated successfully.',
            'failed' => 'Failed to update book.',
        ],
        'delete' => [
            'succeeded' => 'Book deleted successfully.',
            'failed' => 'Failed to delete book.',
        ],
    ],
    'subscribers' => [
        'expiration_minimum_length' => 'Subscription expiration date must be at least :days days from now.',
        'read' => [
            'not_found' => 'Subscriber not found.',
        ],
        'create' => [
            'succeeded' => 'Subscriber created successfully.',
            'failed' => 'Failed to create subscriber.',
        ],
        'update' => [
            'succeeded' => 'Subscriber updated successfully.',
            'failed' => 'Failed to update subscriber.',
        ],
        'delete' => [
            'succeeded' => 'Subscriber deleted successfully.',
            'failed' => 'Failed to delete subscriber.',
        ],
    ],
    'reservation' => [
        'read' => [
            'not_found' => 'Reservation not found.',
        ],
        'reserve' => [
            'succeeded' => 'Reservation created successfully.',
            'failed' => 'Failed to create reservation.',
        ],
        'free' => [
            'succeeded' => 'Reservation freed successfully.',
            'failed' => 'Failed to free reservation.',
        ],
        'history' => [
            'succeeded' => 'Reservation history retrieved successfully.',
            'failed' => 'Failed to retrieve reservation history.',
        ],
        'book' => [
            'is_reserved' => 'The book already reserved.',
        ],
        'report' => [
            'succeeded' => 'Reservation report retrieved successfully.',
            'failed' => 'Failed to retrieve reservation report.',
        ],
        'delete' => [
            'succeeded' => 'Reservation deleted successfully.',
            'failed' => 'Failed to delete reservation.',
        ]
    ]

];