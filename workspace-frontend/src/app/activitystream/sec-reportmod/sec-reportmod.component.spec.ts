import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SecReportmodComponent } from './sec-reportmod.component';

describe('SecReportmodComponent', () => {
  let component: SecReportmodComponent;
  let fixture: ComponentFixture<SecReportmodComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SecReportmodComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SecReportmodComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
