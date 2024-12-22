<?php

namespace Stereoqweex\TestDromPackage\Test;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Stereoqweex\TestDromPackage\Client\BaseHttpClient;
use Stereoqweex\TestDromPackage\Client\ExampleClient;
use Stereoqweex\TestDromPackage\DTO\Request\PostCommentDTO;
use Stereoqweex\TestDromPackage\DTO\Request\PutCommentDTO;
use Stereoqweex\TestDromPackage\DTO\Response\Comment;
use Stereoqweex\TestDromPackage\DTO\Response\CommentList;
use Stereoqweex\TestDromPackage\Exception\CommentNotFoundException;
use Stereoqweex\TestDromPackage\Exception\HttpClientException;
use Stereoqweex\TestDromPackage\Test\Mock\MockHttpClient;

final class ClientTest extends TestCase
{
    private ExampleClient $client;
    private MockHttpClient $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new MockHttpClient();
        $this->client = new ExampleClient($this->httpClient);
    }

    /**
     * @throws HttpClientException
     */
    public function testGetCommentList(): CommentList
    {
        $actualCommentList = $this->client->getCommentsList();
        $expectedCommentList = new CommentList();
        for ($i = 1; $i <= 4; $i++) {
            $expectedCommentList->comments[] = new Comment($i, 'name' . $i, 'text' . $i);
        }

        $this->assertInstanceOf(CommentList::class, $actualCommentList);
        $this->assertEquals($expectedCommentList, $actualCommentList, 'Список комментов не соответствует ожидаемому');

        return $actualCommentList;
    }

    /**
     * @throws HttpClientException
     * @throws CommentNotFoundException
     */
    #[Depends('testGetCommentList')]
    public function testPostAndPutComment(CommentList $expectedCommentList): CommentList
    {
        $postCommentDTO = new PostCommentDTO();
        $postCommentDTO->text = 'text5';
        $postCommentDTO->name = 'name5';

        $actualComment = $this->client->postComment($postCommentDTO);
        $expectedComment = new Comment(5, 'name5', 'text5');
        $expectedCommentList->comments[] = $expectedComment;
        $actualCommentList = $this->client->getCommentsList();

        $this->assertInstanceOf(Comment::class, $actualComment);
        $this->assertEquals($expectedComment, $actualComment, 'Опубликованный коммент не соответствует действительности');
        $this->assertEquals($expectedCommentList, $actualCommentList, 'Опубликованный коммент не отображается в списке комментов');

        foreach ($expectedCommentList->comments as $comment) {
            if (3 === $comment->id) {
                $comment->text = 'changedText3';
                $comment->name = null;
            }
            if (5 === $comment->id) {
                $comment->text = 'changedText5';
                $comment->name = 'changedName5';
            }
        }

        $expectedComment = new Comment(5, 'changedName5', 'changedText5');
        $putCommentDTO = new PutCommentDTO();
        $putCommentDTO->id = 5;
        $putCommentDTO->text = 'changedText5';
        $putCommentDTO->name = 'changedName5';
        $actualComment = $this->client->putComment($putCommentDTO);

        $this->assertInstanceOf(Comment::class, $actualComment);
        $this->assertEquals($expectedComment, $actualComment, 'Измененный коммент не соответствует действительности');

        $expectedComment = new Comment(3, null, 'changedText3');
        $putCommentDTO->id = 3;
        $putCommentDTO->text = 'changedText3';
        $putCommentDTO->name = null;
        $actualComment = $this->client->putComment($putCommentDTO);

        $this->assertInstanceOf(Comment::class, $actualComment);
        $this->assertEquals($expectedComment, $actualComment, 'Измененный коммент не соответствует действительности');

        $actualCommentList = $this->client->getCommentsList();
        $this->assertEquals($expectedCommentList, $actualCommentList, 'Изменения не отображаются в списке комментов');

        $putCommentDTO->id = 8;
        $putCommentDTO->text = 'changedText8';
        $putCommentDTO->name = 'changedName8';

        $this->expectException(CommentNotFoundException::class);
        $this->client->putComment($putCommentDTO);

        return $actualCommentList;
    }
}