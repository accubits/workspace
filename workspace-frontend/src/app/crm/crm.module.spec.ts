import { CrmModule } from './crm.module';

describe('CrmModule', () => {
  let crmModule: CrmModule;

  beforeEach(() => {
    crmModule = new CrmModule();
  });

  it('should create an instance', () => {
    expect(crmModule).toBeTruthy();
  });
});
