package po;

import org.openqa.selenium.*;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import po.User_Add_Save;
import po.Index;

public class User_Add1 {

    @FindBy(xpath = "/HTML[1]/BODY[1]/DIV[1]/DIV[4]/FORM[1]/INPUT[3]")
    private WebElement input_Signup;

    @FindBy(css = "#content > form > input:nth-child(5)")
    private WebElement input_password;

    @FindBy(xpath = "/HTML[1]/BODY[1]/DIV[1]/DIV[4]/FORM[1]/INPUT[3]")
    private WebElement input_Signup_1;

    @FindBy(xpath = "/HTML[1]/BODY[1]/DIV[1]/DIV[2]/A[1]")
    private WebElement a_Div_Div_Div_Emailpassword;

    @FindBy(css = "#content > form > input:nth-child(2)")
    private WebElement input_email;

    private WebDriver driver;

    /**
		Page Object for User_Add1 (state2) 
	*/
    public User_Add1(WebDriver driver) {
        this.driver = driver;
        PageFactory.initElements(driver, this);
    }

    public User_Add_Save goToUser_Add_Save() {
        input_Signup.click();
        return new User_Add_Save(driver);
    }

    public Index goToIndex() {
        a_Div_Div_Div_Emailpassword.click();
        return new Index(driver);
    }

    public void LoginForm(String args0, String args1) {
        input_email.sendKeys(args0);
        input_password.sendKeys(args1);
        input_Signup.click();
    }
}
