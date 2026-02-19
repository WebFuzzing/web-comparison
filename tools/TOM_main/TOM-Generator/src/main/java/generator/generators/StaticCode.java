package generator.generators;

import com.sun.codemodel.*;
import org.junit.Ignore;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.testng.annotations.AfterGroups;
import org.testng.annotations.BeforeGroups;
import org.testng.annotations.BeforeMethod;
import utils.DefaultValues;
import utils.JCodeUtil;
import java.io.File;
import java.io.IOException;
import java.io.PrintStream;
import java.io.RandomAccessFile;
import java.lang.reflect.Method;
import java.util.ArrayList;

/**
 * This class contains all the static code generated
 *
 * @author raphael
 */
public class StaticCode {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    private Settings settings;

    // Browsers
    private static final int CHROME_DRIVER = 1;
    private static final int FIREFOX_DRIVER = 2;
    private static final int SAFARI_DRIVER = 3;
    private static final int OPERA_DRIVER = 4;

    public StaticCode(Settings settings) {
        this.settings = settings;
    }

    public void deleteFile(String path) {
        File file1 = new File(path);
        file1.delete();
    }

    /**
     * Create the File with the all generated code
     *
     * @param cm
     * @param path
     * @throws IOException
     */
    public void closeFile(JCodeModel cm, String path) throws IOException {
        File file1 = new File(path);
        file1.mkdirs();
        // Build the file with all the code
        cm.build(file1, (PrintStream) null);
    }

    /**
     * Write the imports for the generated class
     *
     * @param path
     * @throws IOException
     */
    public void initImports(String path) throws IOException {
        try (RandomAccessFile file = new RandomAccessFile(path + ".java", "rws")) {
            byte[] text = new byte[(int) file.length()];
            file.readFully(text);
            file.seek(0);
            // Change here
            file.writeBytes("package WEBTests" + this.getSettings().getPackageName() + ";\n\n");
            file.writeBytes("import java.io.File;\n");
            file.writeBytes("import java.util.List;\n");
            file.writeBytes("import io.github.bonigarcia.wdm.ChromeDriverManager;\n");
            file.writeBytes("import io.github.bonigarcia.wdm.OperaDriverManager;\n");
            file.writeBytes("import org.apache.commons.io.FileUtils;\n");
            file.writeBytes("import org.apache.http.HttpResponse;\n");
            file.writeBytes("import org.apache.http.client.methods.HttpGet;\n");
            file.writeBytes("import org.apache.http.impl.client.DefaultHttpClient;\n");
            file.writeBytes("import org.openqa.selenium.OutputType;\n");
            file.writeBytes("import org.openqa.selenium.TakesScreenshot;\n");
            file.writeBytes("import org.openqa.selenium.firefox.FirefoxBinary;\n");
            file.writeBytes("import org.openqa.selenium.firefox.FirefoxDriver;\n");
            file.writeBytes("import org.openqa.selenium.firefox.FirefoxProfile;\n");
            file.writeBytes("import org.openqa.selenium.support.ui.WebDriverWait;\n");
            file.writeBytes("import org.openqa.selenium.support.ui.Select;\n");
            file.writeBytes("import org.openqa.selenium.interactions.Actions;\n");
            file.writeBytes("import org.openqa.selenium.WebElement;\n");
            file.writeBytes("import org.openqa.selenium.By;\n");
            file.writeBytes("import org.openqa.selenium.chrome.ChromeDriver;\n");
            file.writeBytes("import org.openqa.selenium.safari.SafariDriver;\n");
            file.writeBytes("import org.openqa.selenium.opera.OperaDriver;\n");
            file.writeBytes("import org.testng.Reporter;\n");
            file.writeBytes("import static org.testng.Assert.fail;\n");
            file.writeBytes("import static org.testng.Assert.assertTrue;\n");
            file.writeBytes("import java.util.concurrent.TimeUnit;\n");
            file.writeBytes("import org.openqa.selenium.*;\n");
            file.writeBytes("import org.openqa.selenium.Alert;\n");
            file.write(text);
            file.close();
        }
    }

    /**
     * Generate a auxiliary function to check for Broken
     *
     * @param cm
     * @param type
     * @param dc
     * @return
     */
    public JMethod generateCheckBroken(JCodeModel cm, JDefinedClass dc, String type) {
        JMethod m = dc.method(JMod.PRIVATE, void.class, "broken" + type);

        m.annotate(Ignore.class);

        String source = "Links".equals(type) ? "href" : "src";

        JExpression newInstance = JExpr.direct("driver.findElements(By.tagName(\"" + type + "\"))");

        JType listwebElement = cm.ref("List<WebElement>");
        m.body().decl(listwebElement, "linkList", newInstance);

        JExpression linkList = JExpr.direct("linkList");
        JType webElement = cm._ref(WebElement.class);

        JBlock foreachBody = m.body().forEach(webElement, "link", linkList).body();

        // Try block
        JTryBlock jTryBlock = foreachBody._try();
        jTryBlock.body().directStatement("HttpResponse response = new DefaultHttpClient().execute(new HttpGet(link.getAttribute(\"" + source + "\")));");
        jTryBlock.body()._if(JExpr.direct("response.getStatusLine().getStatusCode() == 404"))
                ._then().directStatement("Reporter.log(\"[FAIL]:\" + link.getAttribute(\"" + source + "\"));");

        // Finally block
        jTryBlock._finally().directStatement("Reporter.log(\"[Pass]: All " + type + " are good \");");


        // Catch Block
        JCatchBlock catchBlock = jTryBlock._catch(cm.ref(Exception.class));
        catchBlock.body().directStatement("Reporter.log(\"[FAIL]: \" +  link.getAttribute(\"" + source + "\"));");

        return m;
    }

    /**
     * Create an auxiliary method to check if the element is displayed
     *
     * @param cm
     * @param dc
     */
    public void generateIsElementDisplayed(JCodeModel cm, JDefinedClass dc) {
        JMethod m = dc.method(JMod.PRIVATE, boolean.class, "isElementDisplayed");
        m.param(By.class, "by");
        //create a block with try and catch with the validation

        ArrayList<String> body = new ArrayList<>();
        body.add("driver.findElement(by).isDisplayed();");
        body.add("return true;");

        ArrayList<String> catchBody = new ArrayList<>();
        catchBody.add("Reporter.log(\"Element not found\");");
        catchBody.add("return false;");

        JCodeUtil.tryCatchBlock(m.body(), cm, body, catchBody, 0, "", "AssertionError", settings.getScreenShotsPath());
    }

    /**
     * Auxiliary method that init an instance of selenium WebDriver
     *
     * @param m
     */
    public void initSelenium(JMethod m, String url, Settings settings, int i) {
        // Create annotation
        JAnnotationArrayMember annotationArrayMember = m.annotate(BeforeGroups.class).paramArray("groups");
        int2Groups(annotationArrayMember, i);
        m.body().directStatement(chooseWebDriver(settings.getBrowser()));
        m.body().directStatement("this.driver.manage().window().setSize(new Dimension(1280, 800));");
        m.body().directStatement("this.driver.get(\"" + url + "\");");
        m.body().directStatement("this.driver.manage().timeouts().implicitlyWait(10, TimeUnit.SECONDS);");
        m.body().directStatement("WebDriverWait wait = new WebDriverWait(driver, 60);");
    }

    /**
     * Generate the method executed after each Test
     *
     * @param dc
     * @param i
     */
    public void closeSelenium(JDefinedClass dc, int i) {
        // Creates the method
        JMethod m = dc.method(1, void.class, "closeBrowsers");

        // Create an annotation
        JAnnotationArrayMember annotationArrayMember = m.annotate(AfterGroups.class).paramArray("groups");
        int2Groups(annotationArrayMember, i);

        // Write the body of method
        m.body().directStatement("this.driver.close();");
    }

    /**
     * Auxiliary method that init an instance of selenium WebDriver
     *
     * @param m
     */
    public void startTest(JCodeModel jCodeModel, JMethod m) {
        // Create annotation
        m.annotate(BeforeMethod.class);
        // Declare parameter for the method method
        m.param(Method.class, "method");

        // Declare a variable String method_name
        JVar methodName = m.body().decl(jCodeModel.ref(String.class).unboxify(), "method_name");

        // Assign value to the declared variable with return of doScan with argument String -'Letmeshare'
        m.body().assign(methodName, m.params().get(0).invoke("getName"));
        m.body().directStatement("Reporter.log(\"Started test \" + " + methodName.name() + " + \"<br>\" );");
    }

    /**
     * Generate the code to initiate the choosen browser
     *
     * @param webdriver
     * @return
     */
    private String chooseWebDriver(int webdriver) {
        StringBuilder driver = new StringBuilder();
        switch (webdriver) {
            case FIREFOX_DRIVER:
                driver.append("FirefoxProfile profile = new FirefoxProfile();\n\t");
                driver.append("this.driver = new FirefoxDriver(new FirefoxBinary(new File(\"/Applications/Firefox.app/Contents/MacOS/firefox-bin\")), profile);");
                break;
            case CHROME_DRIVER:
                driver.append("ChromeDriverManager.getInstance().setup();\n\t");
                driver.append("\tthis.driver = new ChromeDriver();");
                break;
            case SAFARI_DRIVER:
                driver.append("this.driver = new SafariDriver();\n\t");
                break;
            case OPERA_DRIVER:
                driver.append("OperaDriverManager.getInstance().setup();\n\t");
                driver.append("\tthis.driver = new OperaDriver();");
                break;
            default:
                logger.error("ERROR: WEBDRIVER NOT SELECTED!");
        }

        return driver.toString();
    }

    private void int2Groups(JAnnotationArrayMember annotationArrayMember, int i) {
        for (int j = 1; j < i; j++) {
            annotationArrayMember.param(Integer.toString(j));
        }
    }

    public Settings getSettings() {
        return settings;
    }

    public void setSettings(Settings settings) {
        this.settings = settings;
    }
}
