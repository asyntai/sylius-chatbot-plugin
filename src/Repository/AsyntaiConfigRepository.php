<?php

declare(strict_types=1);

/**
 * Asyntai - AI Chatbot for Sylius
 *
 * @category  Asyntai
 * @package   AsyntaiSyliusChatbotPlugin
 * @author    Asyntai <hello@asyntai.com>
 * @copyright Copyright (c) Asyntai
 * @license   MIT License
 */

namespace Asyntai\SyliusChatbotPlugin\Repository;

use Asyntai\SyliusChatbotPlugin\Entity\AsyntaiConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AsyntaiConfig>
 */
class AsyntaiConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AsyntaiConfig::class);
    }

    public function findByKey(string $key): ?AsyntaiConfig
    {
        return $this->findOneBy(['configKey' => $key]);
    }

    public function getValue(string $key): ?string
    {
        $config = $this->findByKey($key);
        return $config?->getConfigValue();
    }

    public function setValue(string $key, ?string $value): void
    {
        $config = $this->findByKey($key);

        if ($config === null) {
            $config = new AsyntaiConfig();
            $config->setConfigKey($key);
        }

        $config->setConfigValue($value);

        $em = $this->getEntityManager();
        $em->persist($config);
        $em->flush();
    }

    public function deleteByKey(string $key): void
    {
        $config = $this->findByKey($key);

        if ($config !== null) {
            $em = $this->getEntityManager();
            $em->remove($config);
            $em->flush();
        }
    }

    public function deleteAll(): void
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->delete(AsyntaiConfig::class, 'c')->getQuery()->execute();
    }
}
