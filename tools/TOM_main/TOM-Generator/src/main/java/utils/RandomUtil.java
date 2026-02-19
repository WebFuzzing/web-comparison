package utils;

import java.util.Random;

/**
 * @author raphael
 */
public final class RandomUtil {

    public static final String LETTER_UPPER = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    public static final String LETTER_LOWER = "abcdefghijklmnopqrstuvwxyz";
    public static final String DIGIT = "0123456789";
    public static final Random rand = new Random();

    private RandomUtil() {
        throw new IllegalAccessError("Utility class");
    }

    /**
     * Generate a Random String
     *
     * @param prefix
     * @param length
     * @return
     */
    public static String randomString(String prefix, int length) {
        StringBuilder sb = new StringBuilder(prefix == null ? "" : prefix);
        int size = sb.length() + length;
        while (sb.length() < size) {
            sb.append(LETTER_LOWER.charAt(randomNumber(0, LETTER_LOWER.length() - 1)));
        }
        return sb.toString();
    }


    public static int randomNumber(int min, int max) {
        int range = max - min + 1;
        // nextInt is normally exclusive of the top value
        return rand.nextInt(range) + min;
    }

    /**
     * Generate Two Random Numbers
     *
     * @param min
     * @param max
     * @return
     */
    public static int[] generate2Nums(int min, int max) {

        if (min == max) {
            return new int[]{max, min};
        }

        int randNum1 = RandomUtil.randomNumber(min, max);
        int randNum2;
        do {
            randNum2 = RandomUtil.randomNumber(min, max);
        } while (randNum1 == randNum2);

        return new int[]{randNum1, randNum2};
    }
}
