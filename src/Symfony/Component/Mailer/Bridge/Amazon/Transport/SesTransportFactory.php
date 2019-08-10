<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Mailer\Bridge\Amazon\Transport;

use Symfony\Component\Mailer\Exception\UnsupportedSchemeException;
use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
final class SesTransportFactory extends AbstractTransportFactory
{
    public function create(Dsn $dsn): TransportInterface
    {
        $scheme = $dsn->getScheme();
        $user = $this->getUser($dsn);
        $password = $this->getPassword($dsn);
        $region = $dsn->getOption('region');

        if ('api' === $scheme) {
            return new SesApiTransport($user, $password, $region, $this->client, $this->dispatcher, $this->logger);
        }

        if ('http' === $scheme) {
            return new SesHttpTransport($user, $password, $region, $this->client, $this->dispatcher, $this->logger);
        }

        if ('smtp' === $scheme) {
            return new SesSmtpTransport($user, $password, $region, $this->dispatcher, $this->logger);
        }

        throw new UnsupportedSchemeException($dsn, ['api', 'http', 'smtp']);
    }

    public function supports(Dsn $dsn): bool
    {
        return 'ses' === $dsn->getHost();
    }
}
