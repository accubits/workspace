import { TestBed, inject } from '@angular/core/testing';

import { TimeReportApiService } from './time-report-api.service';

describe('TimeReportApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [TimeReportApiService]
    });
  });

  it('should be created', inject([TimeReportApiService], (service: TimeReportApiService) => {
    expect(service).toBeTruthy();
  }));
});
