import { SuperAdminModule } from './super-admin.module';

describe('SuperAdminModule', () => {
  let superAdminModule: SuperAdminModule;

  beforeEach(() => {
    superAdminModule = new SuperAdminModule();
  });

  it('should create an instance', () => {
    expect(superAdminModule).toBeTruthy();
  });
});
