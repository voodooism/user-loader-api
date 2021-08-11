<?php
declare(strict_types=1);

namespace App\UserImporter\DTO;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use Iterator;

class UserCollection implements Countable, ArrayAccess, Iterator
{
    /**
     * Contains a collection of all users.
     *
     * @var User[]
     */
    private array $users = [];

    /**
     * Current number of user.
     */
    private int $count = 0;

    /**
     * Key of the current element.
     */
    private int $current = 0;

    /**
     * @param User[] $users
     */
    public function __construct(array $users = [])
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }
    }

    /**
     * Adds a user to the collection.
     */
    public function addUser(User $user): void
    {
        $this->users[] = $user;
        $this->count++;
    }

    public function iteratorToArray(): array
    {
        return iterator_to_array($this);
    }

    /**
     * @internal
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * @psalm-suppress MixedArrayOffset
     *
     * @internal
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return isset($this->users[$offset]);
    }

    /**
     * @psalm-suppress MixedArrayOffset
     *
     * @internal
     * @inheritDoc
     */
    public function offsetGet($offset): ?User
    {
        return $this->users[$offset] ?? null;
    }

    /**
     * @internal
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        if (!$value instanceof User) {
            throw new InvalidArgumentException(
                sprintf('Value must be an instance of %s', User::class)
            );
        }

        $this->addUser($value);
    }

    /**
     * @psalm-suppress MixedArrayOffset
     *
     * @internal
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        unset($this->users[$offset]);
        --$this->count;
    }

    /**
     * @internal
     * @inheritDoc
     */
    public function current(): User
    {
        return $this->users[$this->current];
    }

    /**
     * @internal
     * @inheritDoc
     */
    public function next(): void
    {
        ++$this->current;
    }

    /**
     * @internal
     * @inheritDoc
     */
    public function key(): int
    {
        return $this->current;
    }

    /**
     * @internal
     * @inheritDoc
     */
    public function valid(): bool
    {
        return isset($this->users[$this->current]);
    }

    /**
     * @internal
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->current = 0;
    }
}
