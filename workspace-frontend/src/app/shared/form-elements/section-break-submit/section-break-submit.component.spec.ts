import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SectionBreakSubmitComponent } from './section-break-submit.component';

describe('SectionBreakSubmitComponent', () => {
  let component: SectionBreakSubmitComponent;
  let fixture: ComponentFixture<SectionBreakSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SectionBreakSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SectionBreakSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
