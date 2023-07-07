<?php /** @noinspection PhpFormatFunctionParametersMismatchInspection */

const LABEL_REPOSITORY = 'REPOSITORY';

const LABEL_ID = 'ID';
const LABEL_TAG = 'TAG';
const LABEL_SIZE = 'SIZE';
const LABEL_VIRTUAL_SIZE = 'VIRTUAL SIZE';
const LABEL_CREATED_AT = 'CREATED AT';

$out = [];
$code = 0;
exec('docker images --format json', $out, $code);

$json = json_decode(sprintf("[%s]", implode(PHP_EOL . ',', $out)));

$idLength = 12;
$repositoryLength = mb_strlen(LABEL_REPOSITORY);
$tagLength = mb_strlen(LABEL_TAG);
$sizeLength = mb_strlen(LABEL_SIZE);
$virtualSizeLength = mb_strlen(LABEL_VIRTUAL_SIZE);
$createdAtLength = 29;
$createdSinceLength = 0;

foreach ($json as $image) {
    $repositoryLength = max($repositoryLength, mb_strlen($image->Repository));
    $tagLength = max($tagLength, mb_strlen($image->Tag));
    $sizeLength = max($sizeLength, mb_strlen($image->Size));
    $virtualSizeLength = max($virtualSizeLength, mb_strlen($image->VirtualSize));
    $createdSinceLength = max($createdSinceLength, mb_strlen($image->CreatedSince));
}

$arr = [
    ['label' => LABEL_ID, 'width' => $idLength],
    ['label' => LABEL_REPOSITORY, 'width' => $repositoryLength],
    ['label' => LABEL_TAG, 'width' => $tagLength],
    ['label' => LABEL_SIZE, 'width' => $sizeLength],
    ['label' => LABEL_VIRTUAL_SIZE, 'width' => $virtualSizeLength],
    ['label' => LABEL_CREATED_AT, 'width' => $createdAtLength + $createdSinceLength + 3],
];

fwrite(STDOUT, getRowBorder($arr));
fwrite(STDOUT, getTableRow($arr));
fwrite(STDOUT, getRowBorder($arr));
foreach ($json as $image) {
    fwrite(STDOUT, getTableRow([
        ['label' => $image->ID, 'width' => $idLength],
        ['label' => $image->Repository, 'width' => $repositoryLength],
        ['label' => $image->Tag, 'width' => $tagLength],
        ['label' => $image->Size, 'width' => $sizeLength],
        ['label' => $image->VirtualSize, 'width' => $virtualSizeLength],
        ['label' => "$image->CreatedAt ($image->CreatedSince)", 'width' => $createdAtLength + $createdSinceLength + 3],
    ]));
}
fwrite(STDOUT, getRowBorder($arr));

function getRowBorder(array $row): string
{
    $line = '';
    $cellCount = count($row);
    for ($i = 1; $i <= $cellCount; $i++) {
        $line .= '+' . str_repeat('-', $row[$i-1]['width'] + 2);
        if ($i === $cellCount) {
            $line .= '+';
        }
    }
    return "$line\n";
}

function getTableRow(array $row): string
{
    $line = '';
    $cellCount = count($row);
    for ($i = 1; $i <= $cellCount; $i++) {
        $line .= sprintf('| %-*s', $row[$i-1]['width'] + 1, $row[$i-1]['label']);
        if ($i === $cellCount) {
            $line .= '|';
        }
    }
    return "$line\n";
}