<?php

namespace Stereoqweex\TestDromPackage\Client;

use Stereoqweex\TestDromPackage\Client\Interface\HttpClientInterface;
use Stereoqweex\TestDromPackage\DTO\Request\PostCommentDTO;
use Stereoqweex\TestDromPackage\DTO\Request\PutCommentDTO;
use Stereoqweex\TestDromPackage\DTO\Response\Comment;
use Stereoqweex\TestDromPackage\DTO\Response\CommentList;
use Stereoqweex\TestDromPackage\Exception\CommentNotFoundException;
use Stereoqweex\TestDromPackage\Exception\HttpClientException;

readonly class ExampleClient
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    /**
     * @throws HttpClientException
     */
    public function getCommentsList(): CommentList
    {
        $response = $this->client->get('/comments');

        $commentList = new CommentList();
        foreach ($response['comments'] as $comment) {
            $commentList->comments[] = new Comment(
                (int) $comment['id'],
                $comment['name'],
                $comment['text'],
            );
        }

        return $commentList;
    }

    /**
     * @throws HttpClientException
     */
    public function postComment(PostCommentDTO $comment): Comment
    {
        $response = $this->client->post('/comments', $comment->toArray());

        return new Comment(
            (int) $response['id'],
            $response['name'],
            $response['text'],
        );
    }

    /**
     * @throws CommentNotFoundException
     * @throws HttpClientException
     */
    public function putComment(PutCommentDTO $comment): Comment
    {
        try {
            $response = $this->client->put("/comments/$comment->id", $comment->toArray());
        } catch (HttpClientException $e) {
            if (404 === $e->getCode()) {
                throw new CommentNotFoundException();
            }
            throw $e;
        }

        return new Comment(
            (int) $response['id'],
            $response['name'],
            $response['text'],
        );
    }
}