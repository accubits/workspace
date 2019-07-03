import { TimeReportModule } from './time-report.module';

describe('TimeReportModule', () => {
  let timeReportModule: TimeReportModule;

  beforeEach(() => {
    timeReportModule = new TimeReportModule();
  });

  it('should create an instance', () => {
    expect(timeReportModule).toBeTruthy();
  });
});
