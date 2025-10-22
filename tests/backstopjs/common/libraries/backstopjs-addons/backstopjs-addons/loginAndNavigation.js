module.exports = async(page, scenario, config) => {

  if (scenario.loginWrapperSelector) {
    const user = scenario.loginUser ?? config.backstopjsAddons.loginUser;
    const pass = scenario.loginPass ?? config.backstopjsAddons.loginPass;

    if (user && pass) {
      await page.waitForSelector(scenario.loginWrapperSelector);
      await page.type(scenario.loginWrapperSelector + ' input[name="name"]', user);
      await page.type(scenario.loginWrapperSelector + ' input[name="pass"]', pass);
      await page.keyboard.press('Enter'); // Enter Key
      await page.waitForNavigation({ waitUntil: 'networkidle0'});

      if (scenario.loginRedirectTo) {
        await page.goto(scenario.loginRedirectTo, {
          waitUntil: 'networkidle0',
        });
      }
    }
  }
};
