import { TestBed, inject } from '@angular/core/testing';

import { TimeReportDataService } from './time-report-data.service';

describe('TimeReportDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [TimeReportDataService]
    });
  });

  it('should be created', inject([TimeReportDataService], (service: TimeReportDataService) => {
    expect(service).toBeTruthy();
  }));
});
