import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AbsenceFilterComponent } from './absence-filter.component';

describe('AbsenceFilterComponent', () => {
  let component: AbsenceFilterComponent;
  let fixture: ComponentFixture<AbsenceFilterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AbsenceFilterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AbsenceFilterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
