<?php

namespace DeathSatan\ThinkOrmUtils\Enums;
enum WhereEnum{
    /**
     * like %value%
     */
    case LIKE;
    /**
     * not like %value%
     */
    case NOT_LIKE;
    /**
     * like value%
     */
    case LIKE_RIGHT;
    /**
     * not like value%
     */
    case NOT_LIKE_RIGHT;
    /**
     * like %value
     */
    case LIKE_LEFT;
    /**
     * not like %value
     */
    case NOT_LIKE_LEFT;
    /**
     * = value
     */
    case EQ;
    /**
     * <> value
     */
    case NE;
    /**
     * < value
     */
    case LT;
    /**
     * > value
     */
    case GT;
    /**
     * >= value
     */
    case GE;
    /**
     * <= value
     */
    case LE;

    /**
     * between value[0] and value[1]
     */
    case BETWEEN;

    /**
     * not between value[0] and value[1]
     */
    case NOT_BETWEEN;

    /**
     * in (value[0],value[1],....,value[n])
     */
    case IN;

    /**
     * not in (value[0],value[1],....,value[n])
     */
    case NOT_IN;

    /**
     * is null
     */
    case NULL;

    /**
     * not is null
     */
    case NOT_NULL;

    /**
     * find_in_set('value',field)
     */
    case FIND_IN_SET;
}