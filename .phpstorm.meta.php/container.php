<?php

namespace PHPSTORM_META {

    override(\Psr\Container\ContainerInterface::get(0), map([
        '' => '@',
    ]));

    override(\Networx\Salt\Container\MutableContainer::get(0), map([
        "" => "@",
    ]));
}
