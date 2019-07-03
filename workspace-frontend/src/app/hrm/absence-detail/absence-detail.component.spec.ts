import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AbsenceDetailComponent } from './absence-detail.component';

describe('AbsenceDetailComponent', () => {
  let component: AbsenceDetailComponent;
  let fixture: ComponentFixture<AbsenceDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AbsenceDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AbsenceDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
