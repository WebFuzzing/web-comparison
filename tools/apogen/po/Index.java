package po;

import org.openqa.selenium.*;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import po.User_Add1;

public class Index {

    @FindBy(xpath = "/HTML[1]/BODY[1]/DIV[1]/DIV[4]/A[1]")
    private WebElement a_Createaccount;

    @FindBy(xpath = "/HTML[1]/BODY[1]/DIV[1]/DIV[2]/A[1]")
    private WebElement a_Div_Div_Div_Userpasswor;

    @FindBy(css = "#LoginForm > input:nth-child(5)")
    private WebElement input_pass;

    @FindBy(xpath = "/HTML[1]/BODY[1]/DIV[1]/DIV[4]/FORM[1]/INPUT[3]")
    private WebElement input_Login_1;

    @FindBy(css = "#LoginForm > input:nth-child(2)")
    private WebElement input_user;

    @FindBy(xpath = "/HTML[1]/BODY[1]/DIV[1]/DIV[4]/FORM[1]/INPUT[3]")
    private WebElement input_Login;

    private WebDriver driver;

    /**
		Page Object for Index (index) 
	*/
    public Index(WebDriver driver) {
        this.driver = driver;
        PageFactory.initElements(driver, this);
    }

    public User_Add1 goToUser_Add1() {
        a_Createaccount.click();
        return new User_Add1(driver);
    }

    public void LoginForm(String args0, String args1) {
        input_user.sendKeys(args0);
        input_pass.sendKeys(args1);
        input_Login.click();
    }
}
