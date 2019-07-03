import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WorktimeInfoComponent } from './worktime-info.component';

describe('WorktimeInfoComponent', () => {
  let component: WorktimeInfoComponent;
  let fixture: ComponentFixture<WorktimeInfoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WorktimeInfoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WorktimeInfoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
