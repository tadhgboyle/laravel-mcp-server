<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools;

enum ResultType
{
    case Text;
    case Image;
    case Audio;
    case Resource;
}
