<?php

/**
 * @template T
 * @psalm-param iterable<T> $iterable
 * @psalm-return array<array-key, T>
 */
function iterable_to_array($iterable): array {
    if ($iterable instanceof Traversable) {
        return iterator_to_array($iterable, true);
    }

    return $iterable;
}

/**
 * @template T
 * @psalm-param array<T> $data
 *
 * @psalm-return ?T
 */
function array_first(array $data) {
    $key = array_key_first($data);
    if (null === $key) {
        return null;
    }

    return $data[$key];
}

/**
 * @template T
 *
 * @psalm-param iterable<T> $items
 * @psalm-param Closure(T):bool $callback
 *
 * @psalm-return T|null
 */
function find_first(iterable $items, Closure $closure) {
    foreach ($items as $item) {
        if ($closure($item)) {
            return $item;
        }
    }

    return null;
}

/**
 * Improved array_map that works with iterables, with static analysis included.
 *
 * @template T
 * @template R
 *
 * @psalm-param iterable<array-key, T> $input
 * @psalm-param Closure(T, array-key):R $callback
 *
 * @psalm-return list<R>
 */
function array_map_i(iterable $input, Closure $callback): array {
    $results = [];
    foreach ($input as $key => $item) {
        $results[] = $callback($item, $key);
    }

    return $results;
}

/**
 * @template T
 *
 * @psalm-param iterable<array-key, T> $iterable
 * @psalm-param Closure(array-key, T):void $callback
 */
function foreach_do(iterable $iterable, Closure $callback): void {
    foreach ($iterable as $key => $value) {
        $callback($key, $value);
    }
}

/**
 * @template T
 *
 * @psalm-param iterable<T> $iterable
 * @psalm-param Closure(T):float $callback
 */
function array_sum_i(iterable $iterable, Closure $callback): float {
    $sum = 0;
    foreach ($iterable as $item) {
        $sum += $callback($item);
    }

    return $sum;
}
