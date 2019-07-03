import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DateResponseComponent } from './date-response.component';

describe('DateResponseComponent', () => {
  let component: DateResponseComponent;
  let fixture: ComponentFixture<DateResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DateResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DateResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
