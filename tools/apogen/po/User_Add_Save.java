package po;

import org.openqa.selenium.*;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class User_Add_Save {

    private WebDriver driver;

    /**
		Page Object for User_Add_Save (state6) 
	*/
    public User_Add_Save(WebDriver driver) {
        this.driver = driver;
        PageFactory.initElements(driver, this);
    }
}
