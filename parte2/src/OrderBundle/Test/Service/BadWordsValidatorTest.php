<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Repository\BadWordsRepositoryInterface;
use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\TestCase;

class BadWordsValidatorTest extends TestCase{

  /**
   * @dataProvider badWordsDataProvider
   */
   public function testHasBadWordsValidator($badWordsList, $message, $expectedResult)
  {
    $badWordsRepository = $this->createMock(BadWordsRepositoryInterface::class);

    $badWordsRepository->method('findAllAsArray')
      ->willReturn($badWordsList);

    $badWordsRepository = new BadWordsValidator($badWordsRepository);

    $hasBadWords = $badWordsRepository->hasBadWords($message);

    $this->assertEquals($expectedResult, $hasBadWords);
  }

  public function badWordsDataProvider()
  {
    return [
      'shouldFindWhenHasBadWords' => [
        'badWordsList' => ['bobo', 'idiota', 'merda'],
        'message' => 'Teu restaurante cheira a merda',
        'foundBadWords' => true
      ],
      'shouldNotFindWhenHasNotBadWords' => [
        'badWordsList' => ['bobo', 'idiota', 'merda'],
        'message' => 'Adicionar bacon nas batatas fritas',
        'foundBadWords' => false
      ],
      'shouldNotFindWhenMessageIsEmpty' => [
        'badWordsList' => ['bobo', 'idiota', 'merda'],
        'message' => '',
        'foundBadWords' => false
      ],
      'shouldNotFindWhenBadWordsListIsEmpty' => [
        'badWordsList' => [],
        'message' => 'Teu restaurante cheira a merda',
        'foundBadWords' => false
      ],

    ];
  }
}