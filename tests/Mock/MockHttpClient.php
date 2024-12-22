<?php

namespace Stereoqweex\TestDromPackage\Test\Mock;

use Stereoqweex\TestDromPackage\Client\Interface\HttpClientInterface;
use Stereoqweex\TestDromPackage\Exception\CommentNotFoundException;
use Stereoqweex\TestDromPackage\Exception\HttpClientException;

class MockHttpClient implements HttpClientInterface
{
    private array $comments = [
        'comments' => [
            ['id' => 1, 'name' => 'name1', 'text' => 'text1'],
            ['id' => 2, 'name' => 'name2', 'text' => 'text2'],
            ['id' => 3, 'name' => 'name3', 'text' => 'text3'],
            ['id' => 4, 'name' => 'name4', 'text' => 'text4'],
        ],
    ];

    public function get(string $url, array $options = []): array
    {
        return $this->comments;
    }

    public function post(string $url, array $json, array $options = []): array
    {
        $id = count($this->comments['comments']) + 1;
        $newComment = array_merge(['id' => $id], $json);
        $this->comments['comments'][] = $newComment;

        return $newComment;
    }

    public function put(string $url, array $json, array $options = []): array
    {
        $urlParts = explode('/', $url);
        $id = (int) array_pop($urlParts);
        $newComment = array_merge(['id' => $id], $json);

        $notFound = true;
        foreach ($this->comments['comments'] as &$comment) {
            if ($comment['id'] === $id) {
                $comment = $newComment;
                $notFound = false;
                break;
            }
        }

        if ($notFound) {
            throw new HttpClientException('Коммент не найден', 404);
        }

        return $newComment;
    }
}