package generator.generators;

/**
 * @author raphael
 */
public class Settings {

    // Choose the browser
    private int browser;

    // Get a screen-shot when error occurs
    private int screenShotOnError;

    // Check for broken-links in each state
    private int checkForBrokenLinks;

    // Check for broken images in each state
    private int checkForBrokenImages;

    // Package name for the generated tests
    private String packageName;

    // Folder for the generated tests
    private String testsPath;

    // Folder for the generated screen-shots
    private String screenShotsPath;

    public Settings(int browser, int screenShotOnError, int checkForBrokenLinks,
                    int checkForBrokenImages, String packageName, String testsPath) {
        this.browser = browser;
        this.screenShotOnError = screenShotOnError;
        this.checkForBrokenLinks = checkForBrokenLinks;
        this.checkForBrokenImages = checkForBrokenImages;
        this.packageName = packageName;
        this.testsPath = testsPath + "WEBTests/";
        this.screenShotsPath = testsPath + "ScreenShots/";
    }

    /**
     * @return the path
     */
    public String getScreenShotsPath() {
        return screenShotsPath;
    }

    /**
     * @return the browser
     */
    public int getBrowser() {
        return browser;
    }

    /**
     * @param browser the browser to set
     */
    public void setBrowser(int browser) {
        this.browser = browser;
    }

    /**
     * @return the screenshot_on_errors
     */
    public int getScreenshotOnErrors() {
        return screenShotOnError;
    }

    /**
     * @param screenShotOnError variable to set
     */
    public void setScreenShotOnError(int screenShotOnError) {
        this.screenShotOnError = screenShotOnError;
    }

    /**
     * @return the checkForBrokenLinks
     */
    public int getCheckForBrokenLinks() {
        return checkForBrokenLinks;
    }

    /**
     * @param checkForBrokenLinks the checkForBrokenLinks to set
     */
    public void setCheckForBrokenLinks(int checkForBrokenLinks) {
        this.checkForBrokenLinks = checkForBrokenLinks;
    }

    /**
     * @return the checkForBrokenImages
     */
    public int getCheckForBrokenImages() {
        return checkForBrokenImages;
    }

    /**
     * @param checkForBrokenImages the checkForBrokenImages to set
     */
    public void setCheckForBrokenImages(int checkForBrokenImages) {
        this.checkForBrokenImages = checkForBrokenImages;
    }


    public String getPackageName() {
        return packageName;
    }

    public void setPackageName(String packageName) {
        this.packageName = packageName;
    }

    public String getTestsPath() {
        return testsPath;
    }

    public void setTestsPath(String testsPath) {
        this.testsPath = testsPath;
    }
}
