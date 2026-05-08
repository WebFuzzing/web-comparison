package po;

import org.openqa.selenium.*;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class Index {

    private WebDriver driver;

    /**
		Page Object for Index (index) 
	*/
    public Index(WebDriver driver) {
        this.driver = driver;
        PageFactory.initElements(driver, this);
    }
}
