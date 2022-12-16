<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\Doctrine\SchemaListener;

use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Symfony\Component\Cache\Adapter\DoctrineDbalAdapter;

/**
 * Automatically adds the cache table needed for the DoctrineDbalAdapter of
 * the Cache component.
 *
 * @author Ryan Weaver <ryan@symfonycasts.com>
 */
final class DoctrineDbalCacheAdapterSchemaSubscriber extends AbstractSchemaSubscriber
{
    private $dbalAdapters;

    /**
     * @param iterable<mixed, DoctrineDbalAdapter> $dbalAdapters
     */
    public function __construct(iterable $dbalAdapters)
    {
        $this->dbalAdapters = $dbalAdapters;
    }

    public function postGenerateSchema(GenerateSchemaEventArgs $event): void
    {
        $connection = $event->getEntityManager()->getConnection();

        foreach ($this->dbalAdapters as $dbalAdapter) {
            $dbalAdapter->configureSchema($event->getSchema(), $connection, $this->getIsSameDatabaseChecker($connection));
        }
    }
}
