import { TimeAndReportsModule } from './time-and-reports.module';

describe('TimeAndReportsModule', () => {
  let timeAndReportsModule: TimeAndReportsModule;

  beforeEach(() => {
    timeAndReportsModule = new TimeAndReportsModule();
  });

  it('should create an instance', () => {
    expect(timeAndReportsModule).toBeTruthy();
  });
});
