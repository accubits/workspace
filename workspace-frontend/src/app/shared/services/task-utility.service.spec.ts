import { TestBed, inject } from '@angular/core/testing';

import { TaskUtilityService } from './task-utility.service';

describe('TaskUtilityService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [TaskUtilityService]
    });
  });

  it('should be created', inject([TaskUtilityService], (service: TaskUtilityService) => {
    expect(service).toBeTruthy();
  }));
});
