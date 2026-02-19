package utils;

import com.sun.codemodel.*;
import org.junit.Ignore;
import org.openqa.selenium.WebDriverException;
import org.openqa.selenium.interactions.Actions;
import parsers.config.files.Mapping;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

/**
 * @author raphaelrodrigues
 */
public final class JCodeUtil {

    private JCodeUtil() {
        throw new IllegalAccessError("Utility class");
    }

    /**
     * write a new line
     *
     * @param jb
     */
    public static void newLine(JBlock jb) {
        jb.directStatement(" ");
    }

    /**
     * Write a comment
     *
     * @param jb
     * @param comment
     */
    public static void comment(JBlock jb, String comment) {
        jb.directStatement(" ");
        jb.directStatement("// " + comment);
    }

    /**
     * Creates a method with Thread.sleep(x);
     *
     * @param dc
     * @return
     */
    public static JMethod wait(JDefinedClass dc) {
        JMethod wait = dc.method(1, void.class, "myWait");
        wait.annotate(Ignore.class);
        wait.body().directStatement("Thread.sleep(3000);");
        wait._throws(InterruptedException.class);
        return wait;
    }

    /**
     * Get the current date
     *
     * @return the current date in a String
     */
    public static String getCurrentDate() {
        DateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
        Date date = new Date();
        return dateFormat.format(date);
    }

    /**
     * Method to create a statement with the Reporter
     *
     * @param jb
     * @param s
     */
    public static void report(JBlock jb, String s) {
        jb.directStatement("Reporter.log(\"" + s + " <br>\"); ");
    }

    /**
     * Generate a block with an action Double click
     *
     * @param cm
     * @param jb
     * @param mappingValue
     */
    public static void doubleClickAction(JCodeModel cm, JBlock jb, Mapping mappingValue, int varNumber) {
        // Create a comment
        JCodeUtil.comment(jb, "double click");
        // Declare Actions and Init
        JVar action = jb.decl(cm.ref(Actions.class).unboxify(), "action" + varNumber, JExpr.direct("new Actions(driver)"));

        // Declare the WebElement and assign
        JType webElement = cm.ref("WebElement");
        JExpression submitBtn = jb.decl(webElement, "submit_btn" + varNumber, JExpr.direct("driver.findElement(By."
                + mappingValue.getHowToFind() + "(\"" + mappingValue.getWhatToFind() + "\"))"));

        // Do the action (double click)
        jb.add(action.invoke("doubleClick").arg(submitBtn).invoke("perform"));
    }

    public static JBlock clickOnMenu(Mapping map) {
        JBlock actionBlock = new JBlock();
        actionBlock.directStatement("Actions actions = new Actions(driver);");
        actionBlock.directStatement("WebElement Mainmenu = driver.findElement(By." + map.getHowToFind()
                + "(\"" + map.getWhatToFind() + "\")); ");
        actionBlock.directStatement("actions." + map.getTypeOfAction() + "(Mainmenu).build().perform();");

        return actionBlock;
    }

    public static String assertTrue(Mapping map) {
        return "assertTrue(driver.findElement(By." + map.getHowToFind() + "(\""
                + map.getWhatToFind() + "\")).isDisplayed());";
    }

    public static String assertNotTrue(Mapping map) {
        return "assertTrue(!driver.findElement(By." + map.getHowToFind() + "(\""
                + map.getWhatToFind() + "\")).isDisplayed());";
    }

    /**
     * Generate a block with an action a click
     *
     * @param jb
     * @param mappingValue
     */
    public static void clickAction(JBlock jb, Mapping mappingValue) {
        jb.directStatement("driver.findElement(By." + mappingValue.getHowToFind()
                + "(\"" + mappingValue.getWhatToFind() + "\"))." + mappingValue.getWhatToDo() + "();");
    }

    /**
     * Check if the submit of form is equals to alert and generate the code for this.
     *
     * @param jb
     * @param mappingValue
     */
    public static void clickAlertAction(JBlock jb, Mapping mappingValue) {
        jb.directStatement("Alert alert=driver.switchTo().alert();");
        jb.directStatement("System.out.println(alert.getText());");
        jb.directStatement("alert." + mappingValue.getWhatToDo() + "();");
    }

    /**
     * Creates a try-catch block
     */
    public static void tryCatchBlock(JBlock jb, JCodeModel cm, List<String> bodyTry, List<String> bodyCath, int gScreenshot,
                                     String mutationLog, String catchException, String screenshotPath) {
        JTryBlock h = jb._try();

        for (String s : bodyTry) {
            h.body().directStatement(s);
        }

        JCatchBlock catchBlock = h._catch(cm.ref(catchException));

        for (String s : bodyCath) {
            catchBlock.body().directStatement(s);
        }

        createScreenShot(gScreenshot, catchBlock, screenshotPath, mutationLog);
    }

    /**
     * Creates a block with try and catch The Catch body has a
     * reporter.log with a MESSAGE of the exception
     *
     * @param jb
     * @param cm
     * @param bodyTry
     * @param bodyCath
     * @param gScreenshot
     * @param mutationLog
     */
    public static void tryCBWithMsgExcept1(JBlock jb, JCodeModel cm, String bodyTry, String bodyCath, int gScreenshot, String mutationLog, String screenshotPath) {
        JTryBlock h = jb._try();
        h.body().directStatement(bodyTry);
        JCatchBlock catchBlock = h._catch(cm.ref(WebDriverException.class));
        catchBlock.body().directStatement("Reporter.log(\" " + bodyCath + " \" + _x.getMessage() ); ");
        createScreenShot(gScreenshot, catchBlock, screenshotPath, mutationLog);
    }

    public static void createScreenShot(int generateScreenshot, JCatchBlock catchBlock, String screenshotPath, String mutationLog) {
        // Check if it is necessary to create a screenshot
        if (generateScreenshot == 1) {
            generateScreenShot(catchBlock.body(), screenshotPath);
        }
        if (!"".equals(mutationLog)) {
            catchBlock.body().directStatement(mutationLog);
        }
    }

    public static void tryCBWithMsgExcept(JBlock jb, JCodeModel cm, JBlock bodyTry, String bodyCath) {
        JTryBlock h = jb._try();
        h.body().add(bodyTry);
        JCatchBlock catchBlock = h._catch(cm.ref(WebDriverException.class));
        catchBlock.body().directStatement("Reporter.log(\" " + bodyCath + " \" + _x.getMessage()   ); ");
        catchBlock.body().directStatement("fail(\" " + bodyCath + " \" + _x.getMessage() ); ");
    }

    /**
     * Generate the code that creates ScreenShots of the application
     *
     * @param jb
     */
    public static void generateScreenShot(JBlock jb, String screenshotPath) {
        jb.block().directStatement("File scrFile = ((TakesScreenshot)driver).getScreenshotAs(OutputType.FILE);");
        jb.block().directStatement("FileUtils.copyFile(scrFile, new File(\"" + screenshotPath + "\"));");
    }

}
